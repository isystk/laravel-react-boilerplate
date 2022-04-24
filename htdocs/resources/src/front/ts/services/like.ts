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
        const response = await API.get(API_ENDPOINT.LIKES);
        this.data = response.likes.data;
        this.main.setAppRoot();
    }

    async addLikeAsync(id: number) {
        this.main.showLoading();
        const response = await API.post(API_ENDPOINT.LIKES_STORE, { id: id });
        if (response.result) {
            window.alert("お気に入りに追加しました");
            const newData: string = id + "";
            this.data = [newData, ...this.data];
        }
        this.main.hideLoading();
        this.main.setAppRoot();
    }

    async removeLikeAsync(id: number) {
        this.main.showLoading();
        const response = await API.post(API_ENDPOINT.LIKES_DESTROY + "/" + id);
        if (response.result) {
            this.data = this.data.filter(n => n !== id + "");
        }
        this.main.hideLoading();
        this.main.setAppRoot();
    }
}
