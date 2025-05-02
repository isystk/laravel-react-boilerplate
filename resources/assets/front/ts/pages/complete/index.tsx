import { Url } from '@/constants/url';
import { Link } from 'react-router-dom';
import BasicLayout from '@/components/templates/BasicLayout';
import useAppRoot from '@/hooks/useAppRoot';

const ShopComplete = () => {
  const [state] = useAppRoot();
  if (!state) return <></>;

  const auth = state.auth;

  return (
    <BasicLayout title="商品購入完了">
      <div className="bg-white p-6 rounded-md shadow-md ">
        <h2 className="font-bold text-xl text-center">
          {auth.name}さん。ご購入ありがとうございました
        </h2>
        <div className="text-center mt-10">
          <p className="mt-5">
            ご登録頂いたメールアドレスへ決済情報をお送りしております。
            <br />
            お手続き完了次第商品を発送致します。
          </p>
          <Link to={Url.top} className="btn btn-primary mt-10">
            商品一覧へ戻る
          </Link>
        </div>
      </div>
    </BasicLayout>
  );
};

export default ShopComplete;
