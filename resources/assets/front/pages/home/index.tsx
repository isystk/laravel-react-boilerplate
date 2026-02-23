import BasicLayout from '@/components/templates/BasicLayout';
import StockItem from '@/components/molecules/StockItem';
import useAppRoot from '@/states/useAppRoot';
import { useEffect } from 'react';
import { useTranslation } from 'react-i18next';

const Home = () => {
  const { t } = useTranslation('auth');
  const { state, service } = useAppRoot();

  useEffect(() => {
    // データを取得
    service.like.readLikesAsync();
    service.orderHistory.readOrderHistory();
  }, [service, state.like.stockIds.length]);

  if (!state) return <></>;

  const { stocks } = state.like;
  const { orders } = state.orderHistory;
  return (
    <BasicLayout title={t('home.title')}>
      {/* お気に入り商品 */}
      <div className="mb-10">
        <h2 className="text-xl font-bold mb-4 border-b pb-2">{t('home.likes')}</h2>
        {stocks && stocks.length > 0 ? (
          <div className="flex flex-wrap">
            {stocks.map((stock: any) => (
              <StockItem
                key={stock.id}
                id={stock.id}
                name={stock.name}
                imageUrl={stock.imageUrl}
                price={stock.price + '円'}
                detail={stock.detail}
                quantity={stock.quantity}
                isLike={true}
                isHome={true}
              />
            ))}
          </div>
        ) : (
          <p className="text-gray-500">お気に入り商品はありません。</p>
        )}
      </div>

      {/* 購入履歴 */}
      <div>
        <h2 className="text-xl font-bold mb-4 border-b pb-2">{t('home.orderHistory')}</h2>
        {orders && orders.length > 0 ? (
          <div className="overflow-x-auto">
            <table className="min-w-full divide-y divide-gray-200">
              <thead className="bg-gray-50">
                <tr>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {t('home.orderId')}
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {t('home.orderDate')}
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {t('home.sumPrice')}
                  </th>
                  <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    商品
                  </th>
                </tr>
              </thead>
              <tbody className="bg-white divide-y divide-gray-200">
                {orders.map((order: any) => (
                  <tr key={order.id}>
                    <td className="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                      #{order.id}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {order.createdAt}
                    </td>
                    <td className="px-6 py-4 whitespace-nowrap text-sm text-red-600 font-bold">
                      {order.sumPrice.toLocaleString()}円
                    </td>
                    <td className="px-6 py-4 text-sm text-gray-500">
                      <ul className="list-disc list-inside">
                        {order.items.map((item: any, idx: number) => (
                          <li key={idx}>
                            {item.stock.name} ({item.quantity})
                          </li>
                        ))}
                      </ul>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        ) : (
          <p className="text-gray-500">購入履歴はありません。</p>
        )}
      </div>
    </BasicLayout>
  );
};

export default Home;
