import Header from "@/components/organisms/Header";
import Footer from "@/components/organisms/Footer";
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
                    {children}
                </main>
            </Circles>
            <Footer/>
        </>
    );
}
