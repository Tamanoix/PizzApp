import {ActionType} from "../action-types/index";
import {Action}from "../actions/index";


interface UserState
{
    loading: boolean;
    error: string | null;
    user: {}[];
    isAuthenticated:boolean;
    token: string;
}

// à l'initialisation, le user est vide
const initialState = {
    loading: false,
    error:null,
    user: [],
    isAuthenticated:false,
    token: ''
};

const userReducer = (
    state: UserState = initialState,
    action:Action

): UserState => {

    switch(action.type)
    {
        case ActionType.LOGIN_USER :
            return {loading:true, error:null, user:[], isAuthenticated:false, token:''};
        case ActionType.LOGIN_USER_SUCCESS :
            return {loading:true, error:null, user:[], isAuthenticated:false, token: action.payload};
        case ActionType.LOGIN_CUSTOMER :
            return {loading:false, error:null, user:action.payload, isAuthenticated:true, token:state.token};
        case ActionType.LOGIN_USER_ERROR :
            return {loading:false, error:action.payload, user:[], isAuthenticated:false, token:''};
        case ActionType.LOGOUT :
            return {loading:false, error:null, user:[], isAuthenticated:false, token:''};
        default:
            return state;
    }
};

export default userReducer;