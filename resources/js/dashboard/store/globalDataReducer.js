const globalDataReducer = (state = {}, action) => {
  let actionType = wp.hooks.applyFilters(
    "login_me_now_dashboard/data_reducer_action",
    action.type
  );
  switch (actionType) {
    case "UPDATE_INITIAL_STATE":
      return {
        ...action.payload,
      };
    case "UPDATE_BLOCK_STATUSES":
      return {
        ...state,
        blocksStatuses: action.payload,
      };
    case "UPDATE_INITIAL_STATE_FLAG":
      return {
        ...state,
        initialStateSetFlag: action.payload,
      };
    case "UPDATE_SETTINGS_ACTIVE_NAVIGATION_TAB":
      return {
        ...state,
        activeSettingsNavigationTab: action.payload,
      };
    case "UPDATE_SOCIAL_LOGIN_ACTIVE_NAVIGATION_TAB":
      return {
        ...state,
        activeSocialLoginNavigationTab: action.payload,
      };
    case "UPDATE_ENABLE_LOAD_FONTS_LOCALLY":
      return {
        ...state,
        enableLoadFontsLocally: action.payload,
      };
    case "UPDATE_ENABLE_LOGS":
      return {
        ...state,
        enableLogs: action.payload,
      };
    case "UPDATE_LOGS_EXPIRATION":
      return {
        ...state,
        logsExpiration: action.payload,
      };

    case "UPDATE_ENABLE_USER_SWITCHING":
      return {
        ...state,
        enableUserSwitching: action.payload,
      };

    case "UPDATE_ENABLE_GOOGLE_LOGIN":
      return {
        ...state,
        enableGoogleLogin: action.payload,
      };
    case "UPDATE_GOOGLE_CLIENT_ID":
      return {
        ...state,
        enableGoogleClientID: action.payload,
      };
    case "UPDATE_GOOGLE_CLIENT_SECRET":
      return {
        ...state,
        enableGoogleClientSecret: action.payload,
      };
    case "UPDATE_ENABLE_GOOGLE_NATIVE_LOGIN":
      return {
        ...state,
        enableGoogleNativeLogin: action.payload,
      };
    case "UPDATE_ENABLE_GOOGLE_UPDATE_EXISTING_USER_DATA":
      return {
        ...state,
        enableGoogleUpdateExistingUserData: action.payload,
      };
    case "UPDATE_ENABLE_GOOGLE_UPDATE_EXISTING_USER_AVATAR":
      return {
        ...state,
        enableGoogleUpdateExistingUserAvatar: action.payload,
      };
    case "UPDATE_ENABLE_CANCEL_ON_TAP_OUTSIDE":
      return {
        ...state,
        enableGoogleCancelOnTapOutside: action.payload,
      };
    case "UPDATE_ENABLE_LOGIN_SELECT_LOCATION":
      return {
        ...state,
        enableGoogleLoginSelectLocation: action.payload,
      };

    case "UPDATE_SELECT_GOOGLE_PRO_DEFAULT_USER_ROLE":
      return {
        ...state,
        selectGoogleProDefaultUserRole: action.payload,
      };

    case "UPDATE_ENABLE_GOOGLE_ONETAP":
      return {
        ...state,
        enableGoogleOneTap: action.payload,
      };

    case "UPDATE_SELECT_GOOGLE_PRO_SELECTED_PAGES":
      return {
        ...state,
        selectGoogleProSelectedPages: action.payload,
      };
    case "UPDATE_SELECT_GOOGLE_PRO_SELECTED_LOCATION":
      return {
        ...state,
        selectGoogleSelectedLocation: action.payload,
      };
    case "UPDATE_INPUT_GOOGLE_PRO_REDIRECT_URL":
      return {
        ...state,
        inputGoogleProRedirectUrl: action.payload,
      };

    case "UPDATE_SETTINGS_SAVED_NOTIFICATION":
      return {
        ...state,
        settingsSavedNotification: action.payload,
      };
    case "UPDATE_SETTINGS_NOT_SAVED_NOTIFICATION":
      return {
        ...state,
        settingsNotSavedNotification: action.payload,
      };
    case "GENERATE_MAGIC_LINK_POPUP":
      return {
        ...state,
        magicLinkPopup: action.payload,
      };

    case "UPDATE_LMN_PRO_LIC":
      return {
        ...state,
        lmnProLic: action.payload,
      };
      
    case "ENABLE_DM_TEMPORARY_LOGIN":
      return {
        ...state,
        dmTemporaryLogin: action.payload,
      };
    case "ENABLE_DM_ADVANCE_SHARE":
      return {
        ...state,
        dmAdvanceShare: action.payload,
      };
    case "ENABLE_DM_SOCIAL_LOGIN":
      return {
        ...state,
        dmSocialLogin: action.payload,
      };
    case "ENABLE_DM_EXPRESS_LOGIN_WC":
      return {
        ...state,
        dmExpressLoginWC: action.payload,
      };
    case "ENABLE_DM_EXPRESS_LOGIN_EDD":
      return {
        ...state,
        dmExpressLoginEDD: action.payload,
      };
    case "ENABLE_DM_EXPRESS_LOGIN_EMAIL":
      return {
        ...state,
        dmExpressLoginEmail: action.payload,
      };
    case "ENABLE_DM_OTP_LOGIN":
      return {
        ...state,
        dmOTPLogin: action.payload,
      };
    case "ENABLE_DM_BROWSER_EXTENSION":
      return {
        ...state,
        dmBrowserExtension: action.payload,
      };
    case "ENABLE_DM_USER_SWITCHING":
      return {
        ...state,
        dmUserSwitching: action.payload,
      };
    case "ENABLE_DM_ACTIVITY_LOGS":
      return {
        ...state,
        dmActivityLog: action.payload,
      };
    case "ENABLE_DM_CONDITIONAL_LOGIN":
      return {
        ...state,
        dmConditionalLogin: action.payload,
      };
    case "ENABLE_SIGN_IN_GOOGLE":
      return {
        ...state,
        lfEnableSignInGoogle: action.payload,
      };
    case "ENABLE_SIGN_IN_FACEBOOK":
      return {
        ...state,
        lfEnableSignInFacebook: action.payload,
      };
    case "ENABLE_SIGN_IN_TWITTER":
      return {
        ...state,
        lfEnableSignInTwitter: action.payload,
      };
    case "LOGIN_LAYOUT":
      return {
        ...state,
        loginLayout: action.payload,
      };
    case "LOGIN_BUTTON_STYLE":
      return {
        ...state,
        loginButtonStyle: action.payload,
      };
    case "UPDATE_FACEBOOK_APP_ID":
      return {
        ...state,
        enableFacebookAppID: action.payload,
      };
    case "UPDATE_FACEBOOK_APP_SECRET":
      return {
        ...state,
        enableFacebookAppSecret: action.payload,
      };
    case "UPDATE_ENABLE_FACEBOOK_LOGIN":
      return {
        ...state,
        enableFacebookLogin: action.payload,
      };
    case "UPDATE_ENABLE_FACEBOOK_NATIVE_LOGIN":
      return {
        ...state,
        enableFacebookNativeLogin: action.payload,
      };
    case "UPDATE_SELECT_FACEBOOK_PRO_DEFAULT_USER_ROLE":
      return {
        ...state,
        selectFacebookProDefaultUserRole: action.payload,
      };
    case "UPDATE_ENABLE_FACEBOOK_UPDATE_EXISTING_USER_DATA":
      return {
        ...state,
        enableFacebookUpdateExistingUserData: action.payload,
      };
    case "UPDATE_ENABLE_FACEBOOK_UPDATE_EXISTING_USER_AVATAR":
      return {
        ...state,
        enableFacebookUpdateExistingUserAvatar: action.payload,
      };
    case "UPDATE_INPUT_FACEBOOK_PRO_REDIRECT_URL":
      return {
        ...state,
        inputFacebookProRedirectUrl: action.payload,
      };
    default:
      return state;
  }
};

export default globalDataReducer;
