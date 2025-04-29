import SessionAlert from "@/components/atoms/SessionAlert";
import BasicLayout from "@/components/templates/BasicLayout";

const Home = () => (
    <BasicLayout title="ダッシュボード">
        <div className="bg-white p-6 rounded-md shadow-md">
            <SessionAlert target="status" />
            ログインが成功しました！
        </div>
    </BasicLayout>
);
export default Home;
