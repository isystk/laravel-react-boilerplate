import Header from "@/components/organisms/Header";
import Footer from "@/components/organisms/Footer";
import SideMenu from "@/components/organisms/SideMenu";
import Icon from "@/components/atoms/Icon";
import Circles from "@/components/atoms/Circles";

type Props = {
    children: React.ReactNode;
    title: string;
    sideMenuId?: string;
    isNew?: boolean;
};


export default function BasicLayout({children, title, sideMenuId, isNew = false}: Readonly<Props>) {

    return (
        <>
            <Header/>
                <Circles>
                    <main className="content">
                        <p className="font-bold text-3xl">React Hooks 全19種の解説とサンプル</p>
                        <div className="grid md:grid-cols-5 pt-10">
                            <div className="md:col-span-4 md:px-5">
                                <div className="flex items-center mb-5">
                                    <h1 className="font-bold text-2xl">{title}</h1>
                                    {isNew && <Icon text="新機能" className="" />}
                                </div>
                                {children}
                            </div>
                            <SideMenu id={sideMenuId} className="md:col-span-1 md:order-first mt-5 md:mt-0" />
                        </div>
                    </main>
                </Circles>
            <Footer/>
        </>
    );
}
