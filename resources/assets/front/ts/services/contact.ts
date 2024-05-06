import MainService from "@/services/main";
import { API } from "@/utilities/api";
import { API_ENDPOINT } from "@/constants/api";

export default class ContactService {
    main: MainService;

    constructor(main: MainService) {
        this.main = main;
    }

    async registContact(values): Promise<boolean> {
        let result = false;
        // ローディングを表示する
        this.main.showLoading();
        try {
            // 入力したお問い合わせ内容を送信する。
            const response = await API.post(API_ENDPOINT.CONTACT_STORE, values);
            result = response.result;
            this.main.setAppRoot();
        } catch (e) {
            alert("お問い合わせの登録に失敗しました");
        }
        // ローディングを非表示にする
        this.main.hideLoading();
        return result;
    }
}
