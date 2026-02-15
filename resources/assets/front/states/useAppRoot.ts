import { useMemo, useCallback } from 'react';
import { useAppState, useAppDispatch } from '@/states/AppContext';
import MainService from '@/services/main';
import RootState from '@/states/root';

const useAppRoot = () => {
  const { root: state } = useAppState();
  const dispatch = useAppDispatch();

  const setRootState = useCallback(
    async (root: RootState) => {
      dispatch({ type: 'SET_STATE', payload: root });
    },
    [dispatch],
  );

  const service = useMemo(() => {
    return new MainService(state, setRootState);
  }, [state, setRootState]);

  return { state, service };
};

export default useAppRoot;
