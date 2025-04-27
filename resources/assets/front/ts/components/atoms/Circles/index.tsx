import styles from './styles.module.scss';
import {ReactNode} from "react";

type Props = {
    children: ReactNode,
};

const Circles = ({ children }: Props) => {
    return (
        <div className={styles.area}>
            <ul className={styles.circles}>
                <li className={styles.circlesLi1}></li>
                <li className={styles.circlesLi2}></li>
                <li className={styles.circlesLi3}></li>
                <li className={styles.circlesLi4}></li>
                <li className={styles.circlesLi5}></li>
                <li className={styles.circlesLi6}></li>
                <li className={styles.circlesLi7}></li>
                <li className={styles.circlesLi8}></li>
                <li className={styles.circlesLi9}></li>
                <li className={styles.circlesLi10}></li>
                <li className={styles.circlesLi11}></li>
                <li className={styles.circlesLi12}></li>
                <li className={styles.circlesLi13}></li>
                <li className={styles.circlesLi14}></li>
                <li className={styles.circlesLi15}></li>
                <li className={styles.circlesLi16}></li>
                <li className={styles.circlesLi17}></li>
                <li className={styles.circlesLi18}></li>
                <li className={styles.circlesLi19}></li>
                <li className={styles.circlesLi20}></li>
            </ul>
            <div className="relative">{children}</div>
        </div>
    );
};

export default Circles;

