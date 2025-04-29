import styles from './styles.module.scss';
import MainService from "@/services/main";
import StockItem, { type Props as StockItemProps } from "@/components/molecules/StockItem";

type Props = {
    stocks: StockItemProps[];
    appRoot: MainService;
}

const StockItems = ({stocks, appRoot}: Props) => {
    return (
        <div className={styles.card}>
            {stocks.map((stock, index) => (
                <StockItem key={index} {...stock} appRoot={appRoot} />
            ))}
        </div>
    );
};

export default StockItems;
