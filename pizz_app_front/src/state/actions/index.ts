import {ActionType} from "../action-types/index";


interface loginUserAction {
    type: ActionType.LOGIN_USER;
}
interface loginUserSuccessAction {
    type: ActionType.LOGIN_USER_SUCCESS;
    payload: '';
}
interface loginUserErrorAction {
    type: ActionType.LOGIN_USER_ERROR
    payload: string;
}

interface logoutAction {
    type:ActionType.LOGOUT
}

interface loginCustomerAction {
    type: ActionType.LOGIN_CUSTOMER;
    payload: {}[];
}


export type Action =
    | loginUserAction
    | loginUserSuccessAction
    | loginUserErrorAction
    | logoutAction
    | loginCustomerAction