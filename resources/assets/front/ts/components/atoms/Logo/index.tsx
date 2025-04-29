import styles from './styles.module.scss';
import Image from '@/components/atoms/Image';
import {APP_NAME} from "@/constants/app";
import {Link} from "react-router-dom";
import { Url } from "@/constants/url";

export type Props = {
    hasLink?: boolean;
}

const Logo = ({hasLink = true}: Props) => {
    return (
        hasLink ? <LinkLogo /> : <NoLinkLogo />
    );
};

const LinkLogo = () => {
    return (
        <Link
            to={Url.TOP}
            className={`flex items-center`}
        >
            <Image src="/assets/front/image/logo.png" width={200} height={60} alt={APP_NAME} className={styles.logoImage} />
        </Link>
    );
};

const NoLinkLogo = () => {
    return (
        <Image src="/assets/front/image/logo.png" width={200} height={60} alt={APP_NAME} className={styles.logoImage} />
    );
};

export default Logo;

