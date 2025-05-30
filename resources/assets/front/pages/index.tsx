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
  imageUrl: string;
  quantity: number;
};

const Top = () => {
  const { state, service } = useAppRoot();

  const navigate = useNavigate();
  const location = useLocation();
  const pageNo = Number(new URLSearchParams(location.search).get('page') || 1);
  const [response, setResponse] = useState({
    currentPage: 1,
    total: 0,
    stocks: [] as Stock[],
  });
  const { total, currentPage, stocks } = response;

  useEffect(() => {
    // お気に入りデータを取得する
    service.like.readLikesAsync();
  }, [service]);

  useEffect(() => {
    (async () => {
      // 商品データを取得する
      const stocks = await service.stock.readStocks(pageNo);
      setResponse(stocks);
    })();
  }, [service, pageNo]);

  const items = useMemo(() => {
    if (!stocks || !state) return [];
    return stocks.map(
      stock =>
        ({
          ...stock,
          price: stock.price + '円',
          isLike: state.like.stockIds.includes(stock.id),
        }) as StockItemProps,
    );
  }, [stocks, state, state?.like.stockIds]); // eslint-disable-line react-hooks/exhaustive-deps

  if (!state) return <></>;

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
              activePage={currentPage}
              totalItemsCount={total}
              itemsCountPerPage={6}
              pageRangeDisplayed={3}
              onChange={pageNo => {
                navigate(`${Url.TOP}?page=${pageNo}`);
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
