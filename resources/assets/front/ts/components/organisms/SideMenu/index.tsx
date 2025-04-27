import styles from './styles.module.scss';
import Icon from "@/components/atoms/Icon";
import { Link } from "react-router-dom";

type SideMenuItem = {
    id: string;
    title: string;
    url: string;
    isNew?: boolean;
}

type Props = {
    id?: string;
    className?: string;
}

const sideMenuItems = [
    {id: 'first', title: 'はじめに', url: '/study'},
    {id: 'useState', title: '01. useState', url: '/study/hooks/useState'},
    {id: 'useEffect', title: '02. useEffect', url: '/study/hooks/useEffect'},
    {id: 'useReducer', title: '03. useReducer', url: '/study/hooks/useReducer'},
    {id: 'useContext', title: '04. useContext', url: '/study/hooks/useContext'},
    {id: 'useRef', title: '05. useRef', url: '/study/hooks/useRef'},
    {id: 'useMemo', title: '06. useMemo', url: '/study/hooks/useMemo'},
    {id: 'useCallback', title: '07. useCallback', url: '/study/hooks/useCallback'},
    {id: 'useLayoutEffect', title: '08. useLayoutEffect', url: '/study/hooks/useLayoutEffect'},
    {id: 'useTransition', title: '09. useTransition', url: '/study/hooks/useTransition'},
    {id: 'useDeferredValue', title: '10. useDeferredValue', url: '/study/hooks/useDeferredValue'},
    {id: 'useId', title: '11. useId', url: '/study/hooks/useId'},
    {id: 'useSyncExternalStore', title: '12. useSyncExternalStore', url: '/study/hooks/useSyncExternalStore'},
    {id: 'useDebugValue', title: '13. useDebugValue', url: '/study/hooks/useDebugValue'},
    {id: 'useImperativeHandle', title: '14. useImperativeHandle', url: '/study/hooks/useImperativeHandle'},
    {id: 'useInsertionEffect', title: '15. useInsertionEffect', url: '/study/hooks/useInsertionEffect'},
    {id: 'useActionState', title: '16. useActionState', url: '/study/hooks/useActionState', isNew: true},
    {id: 'useOptimistic', title: '17. useOptimistic', url: '/study/hooks/useOptimistic', isNew: true},
    {id: 'useFormStatus', title: '18. useFormStatus', url: '/study/hooks/useFormStatus', isNew: true},
    {id: 'use', title: '19. use', url: '/study/hooks/use', isNew: true},
] as SideMenuItem[];

const SideMenu = ({ id, className }: Props) => {
    return (
        <div className={`${className} ${styles.sideMenu}`}>
            <p className={styles.title}>目次</p>
            <ul>
               {sideMenuItems.map(({ id: itemId, title, url, isNew = false }) => (
                    <li key={itemId} className="mb-2">
                        {id === itemId ? (
                            <span >{title}</span>
                        ) : (
                            <Link to={url}>{title}</Link>
                        )}
                        {isNew && <Icon text="New" className="" />}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default SideMenu;
export type {SideMenuItem};
