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

# =========================
# Utility
# =========================

def write_result(message: str):
    with open('review_result.txt', 'w', encoding='utf-8') as f:
        f.write(message)

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
    write_result('✅ 特に問題は見つかりませんでした')
    sys.exit(0)

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
    '- PRの要約は禁止',
    '- コード変更の説明は禁止',
    '- 感想は禁止',
    '- 称賛は禁止',
    '- 問題点のみ指摘',
    '- 問題が無い場合は次の1文のみ返す',
    '  ✅ 特に問題は見つかりませんでした',
    '- diff全文を引用しない',
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
    '### 🔴 Critical',
    '- 内容',
    '',
    '### 🟡 Warning',
    '- 内容',
    '',
    '### 🟢 Suggestion',
    '- 内容',
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
    '以下のGit差分をレビューしてください。問題がある場合は、指示された出力形式に従って問題点のみを簡潔に指摘してください。問題がない場合は「✅ 特に問題は見つかりませんでした」とだけ出力してください。',
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
    write_result(
        '⚠️ GEMINI_API_KEY が未設定です'
    )
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
            write_result(
                '⚠️ Gemini から応答がありません'
            )
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
            write_result(
                '⚠️ Gemini のレスポンス形式が不正です'
            )
            sys.exit(1)

        review_text = (
            parts[0]
            .get('text', '')
            .strip()
        )

        if not review_text:
            review_text = (
                '⚠️ Gemini が空のレビューを返しました'
            )

        # 出力途中切れ検知
        if finish_reason == 'MAX_TOKENS':
            review_text += (
                '\n\n⚠️ レビューが途中で省略されました'
            )

        write_result(review_text)

except urllib.error.HTTPError as e:

    body = e.read().decode(
        'utf-8',
        errors='replace'
    )

    write_result(
        f'❌ Gemini API エラー (HTTP {e.code})\n\n{body}'
    )

    sys.exit(1)

except Exception as e:

    write_result(
        f'❌ Gemini API 呼び出し失敗\n\n{str(e)}'
    )

    sys.exit(1)
