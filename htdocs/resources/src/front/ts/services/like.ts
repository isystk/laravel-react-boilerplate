import MainService from "@/services/main";
import { API } from "@/utilities/api";
import { API_ENDPOINT } from "@/constants/api";

export default class LikeService {
    main: MainService;
    data: string[];

    constructor(main: MainService) {
        this.main = main;
        this.data = [];
    }

    async readLikesAsync() {
        // ローディングを表示する
        this.main.showLoading();
        try {
            const response = await API.get(API_ENDPOINT.LIKES);
            this.data = response.likes.data;
            this.main.setAppRoot();
        } catch (e) {
            alert("お気に入りの取得に失敗しました");
        }
        // ローディングを非表示にする
        this.main.hideLoading();
    }

    async addLikeAsync(id: number) {
        this.main.showLoading();
        try {
            const response = await API.post(API_ENDPOINT.LIKES_STORE, {
                id: id,
            });
            if (response.result) {
                window.alert("お気に入りに追加しました");
                const newData: string = id + "";
                this.data = [newData, ...this.data];
            }
            this.main.setAppRoot();
        } catch (e) {
            alert("お気に入りの追加に失敗しました");
        }
        this.main.hideLoading();
    }

    async removeLikeAsync(id: number) {
        this.main.showLoading();
        try {
            const response = await API.post(
                API_ENDPOINT.LIKES_DESTROY + "/" + id
            );
            if (response.result) {
                this.data = this.data.filter((n) => n !== id + "");
            }
            this.main.setAppRoot();
        } catch (e) {
            alert("お気に入りの削除に失敗しました");
        }
        this.main.hideLoading();
    }
}
