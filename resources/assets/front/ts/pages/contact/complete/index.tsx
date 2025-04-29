import * as React from "react";
import { Url } from "@/constants/url";
import { FC } from "react";
import { Link } from "react-router-dom";
import BasicLayout from "@/components/templates/BasicLayout";

type Props = {
};

const ContactComplete: FC<Props> = () => {
    return (
        <BasicLayout title="お問い合わせ完了">
            <h2
                className="heading02"
                style={{
                    color: "#555555",
                    fontSize: "1.2em",
                    padding: "24px 0px",
                }}
            >
                お問い合わせが完了しました。
            </h2>

            <div className="ta-center">
                <p>
                    お問い合わせが完了しました。担当者から連絡があるまでお待ち下さい。
                </p>
                <Link to={Url.TOP} className="btn text-danger mt40">
                    商品一覧へ戻る
                </Link>
            </div>
        </BasicLayout>
    );
};

export default ContactComplete;
