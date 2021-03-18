// ↓ 取得用のデータ型

export interface Consts
{
    name?: string;
    data?: Const[];
}

export interface Const
{
    code: number;
    text: string;
}

export interface Posts
{
    posts?: Post[];
}

export interface Post
{
    postId: number;
    userId: number;
    title: string;
    text: string;
    registTime: Date;
    registTimeYYYYMMDD: string;
    registTimeMMDD: string;
    imageList: PostImages[];
    tagList: PostTags[];
}

export interface Page
{
    total: number;
    current_page: number;
    last_page: number;
    first_page_url: string;
    prev_page_url: string;
    next_page_url: string;
    last_page_url: string;
}

export interface Stocks
{
    data: Stock[];
    page: Page;
}

export interface Stock
{
    id: number;
    name: string;
    detail: string;
    price: number;
    imgpath: string;
    quantity: number;
    created_at: Date;
    updated_at: Date;
}

export interface PostImages
{
    imageId: number;
    imageUrl: string;
}

export interface PostTags
{
    tagId: number;
    tagName: string;
}

export interface Parts
{
    isShowMv: boolean;
    isShowOverlay: boolean;
    isShowLoading: boolean;
    isSideMenuOpen: boolean;
}

export interface Auth
{
    isLogin: boolean;
    familyName?: string;
}

export interface User
{
    familyName?: string;
    name?: string;
    familyNameKana?: string;
    nameKana?: string;
    email?: string;
    password?: string;
    passwordConf?: string;
    sex?: number;
    zip?: string;
    prefectureId?: number;
    area?: string;
    address?: string;
    building?: string;
    tel?: string;
    birthday?: Date;
}

export interface Remind
{
    isValid: boolean;
}
