import React, { useEffect, FC } from "react";
import Pagination, { ReactJsPaginationProps } from "react-js-pagination";
import { Url } from "@/constants/url";
import TopCarousel from "@/components/pages/shop/TopCarousel";
import Layout from "@/components/Layout";
import MainService from "@/services/main";
import { useLocation, useNavigate } from "react-router-dom";

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

    const renderStocks = (): JSX.Element => (
        <>
            {stocks.map((stock, index) => (
                <div className="block01_item" key={index}>
                    <div className="text-right mb-2">
                        <a
                            href="#"
                            onClick={async (e) => {
                                e.preventDefault();
                                if (stock.isLike) {
                                    await appRoot.like.removeLikeAsync(
                                        stock.id
                                    );
                                } else {
                                    await appRoot.like.addLikeAsync(stock.id);
                                }
                            }}
                            className={`btn btn-sm ${
                                stock.isLike ? "btn-success" : "btn-secondary"
                            }`}
                            data-id="{stock.id}"
                        >
                            気になる
                        </a>
                    </div>
                    <img
                        src={`/uploads/stock/${stock.imgpath}`}
                        alt=""
                        className="block01_img"
                    />
                    <p>{stock.name}</p>
                    <p className="c-red">{stock.price}</p>
                    <p className="mb20">{stock.detail} </p>
                    <form action="/shop/addcart" method="post">
                        <input type="hidden" name="stock_id" value={stock.id} />

                        {stock.quantity === 0 ? (
                            <input
                                type="button"
                                value="カートに入れる（残り0個）"
                                className="btn-gray"
                            />
                        ) : (
                            <input
                                type="button"
                                value={`カートに入れる（残り${stock.quantity}個）`}
                                className="btn-01"
                                onClick={async () => {
                                    if (!appRoot.auth.isLogined) {
                                        // ログインしていない場合はログイン画面に遷移させる
                                        navigate(Url.LOGIN);
                                        return;
                                    }
                                    const result = await appRoot.cart.addCart(
                                        stock.id
                                    );
                                    if (result) {
                                        navigate(Url.MYCART);
                                    }
                                }}
                            />
                        )}
                    </form>
                </div>
            ))}
        </>
    );

    const renderPaging = () => {
        const props = {
            activePage: current_page,
            itemsCountPerPage: 6,
            totalItemsCount: total,
            pageRangeDisplayed: 3,
            onChange: handlePageChange,
            itemClass: "page-item",
            linkClass: "page-link",
        } as ReactJsPaginationProps;
        // @ts-ignore
        return <Pagination {...props} />;
    };

    const handlePageChange = async (pageNo: any) => {
        navigate(`${Url.TOP}?page=${pageNo}`);
    };

    return (
        <Layout appRoot={appRoot} title="TOP">
            <main className="main">
                <div className="contentsArea">
                    <div style={{ marginBottom: "25px" }}>
                        <TopCarousel />
                    </div>
                    <div className="">
                        <div className="block01">{renderStocks()}</div>
                        <div className="mt40">{renderPaging()}</div>
                    </div>
                </div>
            </main>
        </Layout>
    );
};

export default Index;
