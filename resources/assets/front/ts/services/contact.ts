import MainService from "@/services/main";
import { Api } from "@/constants/api";

export default class ContactService {
    main: MainService;

    constructor(main: MainService) {
        this.main = main;
    }
    async registContact(values): Promise<boolean> {
        // ローディングを表示する
        this.main.showLoading();
        try {
            // 入力したお問い合わせ内容を送信する。
            const response = await fetch(Api.contactStore,  {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(values)
            });
            await response.json();
            this.main.setAppRoot();
        } catch (e) {
            alert("お問い合わせの登録に失敗しました");
            this.main.hideLoading();
            return false;
        }
        this.main.hideLoading();
        return true;
    }
}
