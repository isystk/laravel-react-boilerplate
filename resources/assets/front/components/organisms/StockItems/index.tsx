import styles from './styles.module.scss';
import StockItem, { type Props as StockItemProps } from '@/components/molecules/StockItem';
import Pagination from '@/components/atoms/Pagination';
import { useLocation, useNavigate } from 'react-router-dom';
import { useEffect, useMemo } from 'react';
import useAppRoot from '@/states/useAppRoot';
import { Url } from '@/constants/url';

const StockItems = () => {
  const { state, service } = useAppRoot();
  if (!state || !service) return <></>;

  const navigate = useNavigate();
  const location = useLocation();
  const pageNo = Number(new URLSearchParams(location.search).get('page') || 1);

  useEffect(() => {
    // 商品データを取得する
    service.shop.readStocks(pageNo);

    // お気に入りデータを取得する
    service.like.readLikesAsync();
  }, [pageNo]);

  const { total, current_page, data: stockData } = state.shop.stocks;
  const stocks = useMemo(
    () =>
      stockData.map(
        stock =>
          ({
            ...stock,
            price: stock.price + '円',
            isLike: state.like.data.includes(stock.id + ''),
          }) as StockItemProps,
      ),
    [stockData, state.like.data],
  );

  return (
    <>
      <div className={styles.card}>
        {stocks.map((stock, index) => (
          <StockItem key={index} {...stock} />
        ))}
      </div>
      <Pagination
        activePage={current_page}
        totalItemsCount={total}
        itemsCountPerPage={6}
        pageRangeDisplayed={3}
        onChange={pageNo => {
          navigate(`${Url.top}?page=${pageNo}`);
        }}
        className="mt-5 md:mt-10 flex justify-center"
      />
    </>
  );
};

export default StockItems;
