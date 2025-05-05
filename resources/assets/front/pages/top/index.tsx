import BasicLayout from '@/components/templates/BasicLayout';
import Carousel from '@/components/atoms/Carousel';
import banner01Image from '@/assets/images/banner_01.jpg';
import banner02Image from '@/assets/images/banner_02.jpg';
import useAppRoot from '@/states/useAppRoot';
import { useLocation, useNavigate } from 'react-router-dom';
import { useEffect, useMemo, useState } from 'react';
import StockItem, { Props as StockItemProps } from '@/components/molecules/StockItem';
import Pagination from '@/components/atoms/Pagination';
import { Url } from '@/constants/url';

type Stock = {
  id: number;
  name: string;
  detail: string;
  price: number;
  imgpath: string;
  quantity: number;
  created_at: Date;
  updated_at: Date;
  isLike: boolean;
};

const Top = () => {
  const { state, service } = useAppRoot();
  if (!state || !service) return <></>;

  const navigate = useNavigate();
  const location = useLocation();
  const pageNo = Number(new URLSearchParams(location.search).get('page') || 1);
  const [stocks, setStocks] = useState({
    current_page: 1,
    total: 0,
    data: [] as Stock[],
  });
  const { total, current_page } = stocks;

  useEffect(() => {
    // お気に入りデータを取得する
    service.like.readLikesAsync();
  }, []);

  useEffect(() => {
    (async () => {
      // 商品データを取得する
      const stocks = await service.stock.readStocks(pageNo);
      setStocks(stocks);
    })();
  }, [pageNo]);

  const items = useMemo(() => {
    if (!stocks || !state) return [];
    return stocks.data.map(
      stock =>
        ({
          ...stock,
          price: stock.price + '円',
          isLike: state.like.stockIds.includes(stock.id + ''),
        }) as StockItemProps,
    );
  }, [stocks, state.like.stockIds]);

  return (
    <BasicLayout title="TOP">
      <Carousel
        images={[
          { src: banner01Image as string, alt: 'Slide 1' },
          { src: banner02Image as string, alt: 'Slide 2' },
          { src: banner01Image as string, alt: 'Slide 3' },
          { src: banner02Image as string, alt: 'Slide 4' },
        ]}
        autoPlay={true}
        autoPlayInterval={5000}
      />
      <div className="mt-5 md:mt-10">
        {items && (
          <>
            <div className="flex flex-wrap">
              {items.map((item, index) => (
                <StockItem key={index} {...item} />
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
        )}
      </div>
    </BasicLayout>
  );
};

export default Top;
