import { createStore } from "redux";
import globalDataReducer from "./globalDataReducer";

const initialState = wp.hooks.applyFilters("login_me_now_dashboard/datastore", {
  initialStateSetFlag: false,
  enableLoadFontsLocally: false,

  lmnProLic: "",

  enableLogs: true,
  logsExpiration: 7,

  enableUserSwitching: false,

  enableGoogleLogin: false,
  enableGoogleClientID: "",
  enableGoogleClientSecret: "",
  enableGoogleNativeLogin: true,
  enableGoogleAutoSignIn: false,
  enableGoogleUpdateExistingUserData: true,
  enableGoogleUpdateExistingUserAvatar: false,
  enableGoogleCancelOnTapOutside: true,
  enableGoogleLoginSelectLocation: true,

  selectGoogleProSelectedPages: [],
  enableGoogleOneTap: false,
  selectGoogleSelectedLocation: "siteWide",
  selectGoogleProDefaultUserRole: "subscriber",
  inputGoogleProRedirectUrl: "",


  getUserRoles: "subscriber",
  get_pages: "",

  enableWhiteLabel: false,
  enableBeta: "disable",
  settingsSavedNotification: "",
  settingsNotSavedNotification: "",
  magicLinkPopup: "",
  blocksStatuses: [],
  enableFileGeneration: "disable",
  activeSettingsNavigationTab: "",

  dmTemporaryLogin: false,
  dmAdvanceShare: false,
  dmSocialLogin: false,
  dmExpressLoginWC: false,
  dmExpressLoginEDD: false,
  dmExpressLoginEmail: false,
  dmOTPLogin: false,
  dmBrowserExtension: false,
  dmUserSwitching: false,
  dmActivityLog: false,
  dmConditionalLogin: false,
  activeSocialLoginNavigationTab: "",
  lfEnableSignInGoogle: false,
  lfEnableSignInFacebook: false,
  lfEnableSignInTwitter: false,
  loginLayout: "",
  loginButtonStyle: "",
  enableFacebookAppID: "",
  enableFacebookAppSecret: "",
  enableFacebookLogin: false,
  enableFacebookNativeLogin: true,
  selectFacebookProDefaultUserRole: "subscriber",
  enableFacebookUpdateExistingUserData: true,
  enableFacebookUpdateExistingUserAvatar: true,
  inputFacebookProRedirectUrl: ''
});

const globalDataStore = createStore(
  globalDataReducer,
  initialState,
  window.__REDUX_DEVTOOLS_EXTENSION__ && window.__REDUX_DEVTOOLS_EXTENSION__()
);

export default globalDataStore;
