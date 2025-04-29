import styles from './styles.module.scss';
import StockItem, { type Props as StockItemProps } from "@/components/molecules/StockItem";

type Props = {
    stocks: StockItemProps[];
}

const StockItems = ({stocks}: Props) => {
    return (
        <div className={styles.card}>
            {stocks.map((stock, index) => (
                <StockItem key={index} {...stock} />
            ))}
        </div>
    );
};

export default StockItems;
