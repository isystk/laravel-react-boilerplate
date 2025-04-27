import './styles.module.scss';
import Image from '@/components/atoms/Image';
import {APP_NAME} from "@/constants/app";
import {Link} from "react-router-dom";
import { Url } from "@/constants/url";

export type Props = object

const Logo = () => {
    return (
        <Link
            to={Url.TOP}
            className={`flex items-center`}
        >
            <Image src="/assets/front/image/logo.png" width={200} height={60} alt={APP_NAME} className="h-10 md:h-auto" />
        </Link>
    );
};

export default Logo;

