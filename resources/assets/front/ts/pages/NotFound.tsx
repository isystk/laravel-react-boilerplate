import React from "react";
import MainService from "@/services/main";
import BasicLayout from "@/components/templates/BasicLayout";

const NotFound = () => {
    return (
        <BasicLayout title="Not Found">
            <h1>お探しのページは見つかりません。</h1>
        </BasicLayout>
    );
};

export default NotFound;
