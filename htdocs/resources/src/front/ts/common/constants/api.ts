import Env from "../env/";

/**
 * BFF（バックエンドフォーフロントエンド）用の URL を作成する
 * @param path
 */
const getBffUrl = (path: string): string =>
{
    let url: string;
    url = [Env.externalEndpointUrl, path].join("");
    return url;
};

/** API のエンドポイント */
export const API_ENDPOINT = {
    /** ログイン状態チェック */
    LOGIN_CHECK: getBffUrl("/loginCheck"),
    /** ログイン */
    LOGIN: getBffUrl("/authenticate"),
    /** ログアウト */
    LOGOUT: getBffUrl("/logout"),
    /** 共通定数 */
    COMMON_CONST: getBffUrl("/common/const"),

    /** 商品一覧 */
    SHOPS: getBffUrl("/shops"),
    /** 投稿一覧 */
    POSTS: getBffUrl("/posts"),
    //   /** 投稿詳細 */
    //   POSTS_DETAIL: getBffUrl("/posts/${id}"),
    /** マイページ投稿一覧 */
    MEMBER_POSTS: getBffUrl("/member/posts"),
    //   /** マイページ投稿詳細 */
    //   MEMBER_POSTS_DETAIL: getBffUrl("/member/posts/p${id}"),
    /** マイページ投稿 新規登録 */
    MEMBER_POSTS_NEW: getBffUrl("/member/posts/new"),
    //   /** マイページ投稿 変更 */
    //   MEMBER_POSTS_EDIT: getBffUrl("/member/posts/p${values.postId}/edit"),
    //   /** マイページ投稿 削除 */
    //   MEMBER_POSTS_DELETE: getBffUrl("/member/posts/p${values.postId}/delete"),

    /** マイページ投稿 画像アップロード */
    MEMBER_FILE_UPLOAD: getBffUrl("/member/fileupload/image"),
    /** 会員登録 仮登録 */
    ENTRY_REGIST: getBffUrl("/entry/regist"),
    //   /** 会員登録 登録完了 */
    //   ENTRY_REGIST_COMPLETE: getBffUrl("/entry/regist/${onetimeKey}"),

    /** パスワード忘れ メール送信 */
    ENTRY_REMIND: getBffUrl("/entry/remind"),
    /** パスワード忘れ パスワード変更 */
    ENTRY_REMIND_CONFIG: getBffUrl("/entry/remind/config"),

};

