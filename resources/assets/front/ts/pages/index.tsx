import React, { useEffect, FC } from "react";
import Pagination, { ReactJsPaginationProps } from "react-js-pagination";
import { Url } from "@/constants/url";
import TopCarousel from "@/components/pages/shop/TopCarousel";
import Layout from "@/components/Layout";
import MainService from "@/services/main";
import { useLocation, useNavigate } from "react-router-dom";
import BasicLayout from "@/components/templates/BasicLayout";

type Props = {
    appRoot: MainService;
};

const Index: FC<Props> = ({ appRoot }) => {
    const navigate = useNavigate();
    const { search } = useLocation();
    const stocks = appRoot.shop.stocks.data.map((stock) => ({
        ...stock,
        price: stock.price + "円",
        isLike: appRoot.like.data.includes(stock.id + ""),
    }));
    const { total, current_page } = appRoot.shop.stocks;

    useEffect(() => {
        // 商品データを取得する
        appRoot.shop.readStocks(search);

        // お気に入りデータを取得する
        appRoot.like.readLikesAsync();
    }, [search]);

    return (
        <BasicLayout title="TOP">
            xxx
        </BasicLayout>
    );
};

export default Index;
