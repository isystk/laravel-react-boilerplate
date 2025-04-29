import BasicLayout from "@/components/templates/BasicLayout";

const NotFound = () => {
    return (
        <BasicLayout title="Not Found">
            <div className="bg-white h-100 flex items-center justify-center rounded-md shadow-md">
                <h2 className="text-center font-bold text-1xl">お探しのページは見つかりません</h2>
            </div>
        </BasicLayout>
    );
};

export default NotFound;
