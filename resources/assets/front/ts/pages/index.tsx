import React, { useEffect, FC } from "react";
import MainService from "@/services/main";
import { useLocation, useNavigate } from "react-router-dom";
import BasicLayout from "@/components/templates/BasicLayout";
import { Url } from "@/constants/url";
import Pagination from "@/components/atoms/Pagination";
import Carousel from "@/components/atoms/Carousel";
import { type Props as StockItemProps } from "@/components/molecules/StockItem";
import StockItems from "@/components/organisms/StockItems";

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
    } as StockItemProps));
    const { total, current_page } = appRoot.shop.stocks;

    useEffect(() => {
        // 商品データを取得する
        appRoot.shop.readStocks(search);

        // お気に入りデータを取得する
        appRoot.like.readLikesAsync();
    }, [search]);

    return (
        <BasicLayout title="TOP">
            <Carousel images={[
                { src: '/assets/front/image/bunner_01.jpg', alt: 'Slide 1' },
                { src: '/assets/front/image/bunner_02.jpg', alt: 'Slide 2' },
                { src: '/assets/front/image/bunner_01.jpg', alt: 'Slide 3' },
                { src: '/assets/front/image/bunner_02.jpg', alt: 'Slide 4' },
            ]} />
            <div className="mt-10">
                <StockItems stocks={stocks}  appRoot={appRoot}/>
                <Pagination
                    activePage={current_page}
                    totalItemsCount={total}
                    itemsCountPerPage="6"
                    pageRangeDisplayed="3"
                    onChange={(pageNo) => {
                        navigate(`${Url.TOP}?page=${pageNo}`);
                    }}
                />
            </div>
        </BasicLayout>
    );
};

export default Index;
