import glob
import json
import urllib.request
import urllib.error
import os
import sys
import re

# =========================
# 設定
# =========================

MAX_DIFF_CHARS = 20000
MAX_RULE_CHARS = 10000
MAX_OUTPUT_TOKENS = 4096

SEVERITY_ORDER = {'Critical': 0, 'Warning': 1, 'Suggestion': 2}

RESULT_JSON_PATH = 'review_result.json'

# =========================
# Utility
# =========================

def write_result(summary: str, findings=None, skip=False, skip_reason=None):
    payload = {
        'skip': skip,
        'skip_reason': skip_reason,
        'summary': summary,
        'findings': findings or [],
    }
    with open(RESULT_JSON_PATH, 'w', encoding='utf-8') as f:
        json.dump(payload, f, ensure_ascii=False, indent=2)

def safe_read(path):
    try:
        with open(path, 'r', encoding='utf-8', errors='replace') as f:
            return f.read()
    except Exception:
        return ''

# =========================
# Diff 読み込み
# =========================

diff = safe_read('pr_diff.txt')

if not diff.strip():
    write_result('特に問題は見つかりませんでした')
    sys.exit(0)

# =========================
# 差分から「コメント可能な新ファイル側行番号」を抽出
# GitHubのPRレビューAPIはdiffのhunkに含まれる行（追加行・コンテキスト行）にしか
# インラインコメントを付けられないため、指摘の行番号をここで検証・補正する。
# =========================

def parse_valid_lines(diff_text):
    """
    戻り値: { file_path: sorted([line_no, ...]) }
    file_path は 'b/' プレフィックスを除いた新ファイル側のパス。
    """
    valid_lines = {}
    current_file = None
    new_lineno = None

    file_header_re = re.compile(r'^\+\+\+ b/(.+)$')
    hunk_header_re = re.compile(r'^@@ -\d+(?:,\d+)? \+(\d+)(?:,\d+)? @@')

    for line in diff_text.splitlines():
        m_file = file_header_re.match(line)
        if m_file:
            current_file = m_file.group(1)
            valid_lines.setdefault(current_file, [])
            new_lineno = None
            continue

        m_hunk = hunk_header_re.match(line)
        if m_hunk:
            new_lineno = int(m_hunk.group(1))
            continue

        if new_lineno is None or current_file is None:
            continue

        if line.startswith('+') and not line.startswith('+++'):
            valid_lines[current_file].append(new_lineno)
            new_lineno += 1
        elif line.startswith('-') and not line.startswith('---'):
            # 削除行は新ファイル側に存在しないため行番号を進めない
            continue
        else:
            # コンテキスト行
            valid_lines[current_file].append(new_lineno)
            new_lineno += 1

    return {f: sorted(set(ls)) for f, ls in valid_lines.items()}

VALID_LINES = parse_valid_lines(diff)

def resolve_line(file_path, line_no):
    """
    指摘の (file, line) がdiffのコメント可能行に含まれるか検証する。
    含まれない場合は同ファイル内で最も近い有効行に補正する。
    ファイル自体がdiffに存在しない場合は None を返す。
    戻り値: (resolved_line, corrected: bool) または (None, False)
    """
    lines = VALID_LINES.get(file_path)
    if not lines:
        return None, False

    if line_no in lines:
        return line_no, False

    nearest = min(lines, key=lambda l: abs(l - line_no))
    return nearest, True

# =========================
# ノイズ除去
# =========================

# Binary
diff = re.sub(
    r'Binary files .* differ',
    '[Binary file omitted]',
    diff
)

# Minified JS
diff = re.sub(
    r'diff --git a/.*?\.min\.js.*?(?=diff --git|\Z)',
    '[Minified JS omitted]\n',
    diff,
    flags=re.S
)

# package-lock など
diff = re.sub(
    r'diff --git a/.*?(package-lock\.json|yarn\.lock|pnpm-lock\.yaml).*?(?=diff --git|\Z)',
    '[Lock file omitted]\n',
    diff,
    flags=re.S
)

# =========================
# サイズ制限
# =========================

if len(diff) > MAX_DIFF_CHARS:
    diff = (
        diff[:MAX_DIFF_CHARS] +
        '\n\n... (差分が長いため省略)'
    )

# =========================
# Coding Rules
# =========================

coding_rules = ''

for path in sorted(glob.glob('documents/*cording_rule*')):
    content = safe_read(path)

    if not content.strip():
        continue

    coding_rules += (
        f'### {os.path.basename(path)}\n'
        f'{content}\n\n'
    )

if len(coding_rules) > MAX_RULE_CHARS:
    coding_rules = (
        coding_rules[:MAX_RULE_CHARS] +
        '\n... (省略)'
    )

# =========================
# Prompt
# =========================

system_instruction_parts = [
    'あなたは厳格なシニアソフトウェアレビュアーです。',
    '',
    'Pull Request の差分レビューをしてください。',
    '',
    '## 絶対ルール',
    '- comment フィールドは必ず日本語で書くこと（英語禁止）',
    '- PRの要約は禁止',
    '- コード変更の説明は禁止',
    '- 感想は禁止',
    '- 称賛は禁止',
    '- 問題点のみ指摘',
    '- コード引用は最大10行',
    '- 長文コードブロック禁止',
    '- 推測ベースの指摘禁止',
    '- diffに存在しないコードへの言及禁止',
    '- 軽微なスタイル指摘禁止',
    '- 最大5件まで',
    '',
    '## 優先レビュー観点',
    '1. バグ',
    '2. セキュリティ',
    '3. Null安全性',
    '4. 型安全性',
    '5. パフォーマンス',
    '6. 保守性',
    '',
    '## 出力形式',
    '必ず次のJSONスキーマに従うJSON配列のみを出力してください（前後の説明文・コードフェンス禁止）。',
    '問題が無い場合は空配列 [] を返してください。',
    '',
    '[',
    '  {',
    '    "file": "diffの +++ b/ に現れるファイルパス（b/ は含めない）",',
    '    "line": diffのhunk内に実際に存在する新ファイル側の行番号（整数）,',
    '    "severity": "Critical" | "Warning" | "Suggestion",',
    '    "comment": "指摘内容と修正案"',
    '  }',
    ']',
    '',
    '- line は必ずdiffのhunkヘッダ（@@ -a,b +c,d @@）から正確に計算すること',
    '- diffに含まれないファイル・行を指摘しないこと',
]

system_instruction = '\n'.join(system_instruction_parts)

user_content_parts = []

if coding_rules:
    user_content_parts += [
        '## コーディング規約',
        coding_rules,
        '',
    ]

user_content_parts += [
    '以下のGit差分をレビューしてください。指示された出力形式（JSON配列）に厳密に従ってください。',
    '',
    '## Git差分',
    '```diff',
    diff,
    '```',
]

user_content = '\n'.join(user_content_parts)

# =========================
# API Key
# =========================

api_key = os.environ.get('GEMINI_API_KEY', '')

if not api_key:
    write_result('GEMINI_API_KEY が未設定です')
    sys.exit(1)

# =========================
# Gemini Request
# =========================

data = {
    'contents': [
        {
            'parts': [
                {
                    'text': user_content
                }
            ]
        }
    ],
    'systemInstruction': {
        'parts': [
            {
                'text': system_instruction
            }
        ]
    },
    'generationConfig': {
        'temperature': 0.1,
        'maxOutputTokens': MAX_OUTPUT_TOKENS,
        'topP': 0.8,
        'topK': 20,
        'responseMimeType': 'application/json',
    }
}

url = (
    'https://generativelanguage.googleapis.com/'
    f'v1beta/models/gemini-3.1-flash-lite:generateContent?key={api_key}'
)

req = urllib.request.Request(
    url,
    data=json.dumps(data).encode('utf-8'),
    headers={
        'Content-Type': 'application/json'
    },
    method='POST',
)

# =========================
# API Call
# =========================

try:
    with urllib.request.urlopen(req, timeout=120) as response:

        result = json.loads(
            response.read().decode('utf-8')
        )

        candidates = result.get('candidates', [])

        if not candidates:
            write_result('Gemini から応答がありません')
            sys.exit(1)

        candidate = candidates[0]

        finish_reason = candidate.get(
            'finishReason',
            ''
        )

        parts = (
            candidate
            .get('content', {})
            .get('parts', [])
        )

        if not parts:
            write_result('Gemini のレスポンス形式が不正です')
            sys.exit(1)

        raw_text = (
            parts[0]
            .get('text', '')
            .strip()
        )

        if not raw_text:
            write_result('Gemini が空のレビューを返しました')
            sys.exit(0)

        # =========================
        # JSON パース + 行番号検証・補正
        # =========================

        try:
            raw_findings = json.loads(raw_text)
            if not isinstance(raw_findings, list):
                raise ValueError('response is not a JSON array')
        except Exception:
            # JSONとして解釈できない場合は、レビュー全文をサマリーとして扱う
            summary = raw_text
            if finish_reason == 'MAX_TOKENS':
                summary += '\n\n⚠️ レビューが途中で省略されました'
            write_result(summary)
            sys.exit(0)

        findings = []
        skipped_findings = []

        for item in raw_findings:
            if not isinstance(item, dict):
                continue

            file_path = str(item.get('file', '')).strip()
            severity = str(item.get('severity', '')).strip()
            comment = str(item.get('comment', '')).strip()

            try:
                line_no = int(item.get('line'))
            except (TypeError, ValueError):
                line_no = None

            if not file_path or not comment or line_no is None:
                continue

            if severity not in SEVERITY_ORDER:
                severity = 'Suggestion'

            resolved_line, corrected = resolve_line(file_path, line_no)

            if resolved_line is None:
                # diffに存在しないファイル → インラインコメント化できないためサマリー行として扱う
                skipped_findings.append(
                    f'- [{severity}] {file_path}:{line_no} {comment}'
                )
                continue

            if corrected:
                comment = f'(行番号を自動補正: 元指摘は{line_no}行目) {comment}'

            findings.append({
                'file': file_path,
                'line': resolved_line,
                'severity': severity,
                'comment': comment,
            })

        findings.sort(key=lambda f: SEVERITY_ORDER.get(f['severity'], 9))

        if not findings and not skipped_findings:
            summary = '特に問題は見つかりませんでした'
        else:
            summary_parts = [f'{len(findings)}件の指摘があります']
            if skipped_findings:
                summary_parts.append('')
                summary_parts.append('## インラインコメント化できなかった指摘（差分の文脈外）')
                summary_parts += skipped_findings
            summary = '\n'.join(summary_parts)

        if finish_reason == 'MAX_TOKENS':
            summary += '\n\n⚠️ レビューが途中で省略されました'

        write_result(summary, findings=findings)

except urllib.error.HTTPError as e:

    body = e.read().decode(
        'utf-8',
        errors='replace'
    )

    write_result(f'Gemini API エラー (HTTP {e.code})\n\n{body}')

    sys.exit(1)

except Exception as e:

    write_result(f'Gemini API 呼び出し失敗\n\n{str(e)}')

    sys.exit(1)
