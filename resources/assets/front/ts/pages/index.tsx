import { useEffect } from "react";
import { type Props as StockItemProps } from "@/components/molecules/StockItem";
import { Url } from "@/constants/url";
import { useLocation, useNavigate } from "react-router-dom";
import BasicLayout from "@/components/templates/BasicLayout";
import Carousel from "@/components/atoms/Carousel";
import Pagination from "@/components/atoms/Pagination";
import StockItems from "@/components/organisms/StockItems";
import useAppRoot from "@/stores/useAppRoot";

const Index = () => {
    const appRoot = useAppRoot();
    if (!appRoot) return <></>;

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
                    { src: '/assets/front/image/banner_01.jpg', alt: 'Slide 1' },
                    { src: '/assets/front/image/banner_02.jpg', alt: 'Slide 2' },
                    { src: '/assets/front/image/banner_01.jpg', alt: 'Slide 3' },
                    { src: '/assets/front/image/banner_02.jpg', alt: 'Slide 4' },
                ]}
                autoPlay={true}
                autoPlayInterval={5000} // 5秒ごとにスライド
            />
            <div className="mt-5 md:mt-10">
                <StockItems stocks={stocks} />
                <Pagination
                    activePage={current_page}
                    totalItemsCount={total}
                    itemsCountPerPage={6}
                    pageRangeDisplayed={3}
                    onChange={(pageNo) => {
                        navigate(`${Url.TOP}?page=${pageNo}`);
                    }}
                    className="mt-5 md:mt-10 flex justify-center"
                />
            </div>
        </BasicLayout>
    );
};

export default Index;
