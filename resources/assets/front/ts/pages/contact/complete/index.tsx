import { Url } from "@/constants/url";
import { Link } from "react-router-dom";
import BasicLayout from "@/components/templates/BasicLayout";

const ContactComplete = () => {
    return (
        <BasicLayout title="お問い合わせ完了">
            <div className="bg-white p-6 rounded-md shadow-md">
                <h2 className="font-bold text-xl text-center">お問い合わせが完了しました</h2>
                <div className="text-center mt-10">
                    <p className="mt-5">
                        お問い合わせが完了しました。担当者から連絡があるまでお待ち下さい。
                    </p>
                    <Link to={Url.top} className="btn btn-primary mt-10">
                        商品一覧へ戻る
                    </Link>
                </div>
            </div>
        </BasicLayout>
    );
};

export default ContactComplete;
