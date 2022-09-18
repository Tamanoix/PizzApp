import {RootState} from '../index';
import {useSelector, TypedUseSelectorHook} from "react-redux";
export const useTypedSelector:TypedUseSelectorHook<RootState> = useSelector;