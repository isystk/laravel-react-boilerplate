import { useEffect, useMemo } from 'react';
import { type Props as StockItemProps } from '@/components/molecules/StockItem';
import { Url } from '@/constants/url';
import { useLocation, useNavigate } from 'react-router-dom';
import BasicLayout from '@/components/templates/BasicLayout';
import Carousel from '@/components/atoms/Carousel';
import Pagination from '@/components/atoms/Pagination';
import StockItems from '@/components/organisms/StockItems';
import useAppRoot from '@/hooks/useAppRoot';

const Top = () => {
  const [state, service] = useAppRoot();
  if (!state) return <></>;

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
    <BasicLayout title="TOP">
      <Carousel
        images={[
          { src: '/assets/front/image/banner_01.jpg', alt: 'Slide 1' },
          { src: '/assets/front/image/banner_02.jpg', alt: 'Slide 2' },
          { src: '/assets/front/image/banner_01.jpg', alt: 'Slide 3' },
          { src: '/assets/front/image/banner_02.jpg', alt: 'Slide 4' },
        ]}
        autoPlay={true}
        autoPlayInterval={5000}
      />
      <div className="mt-5 md:mt-10">
        <StockItems stocks={stocks} />
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
      </div>
    </BasicLayout>
  );
};

export default Top;
