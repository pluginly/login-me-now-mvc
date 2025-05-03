import React from "react";
import { useLocation } from "react-router-dom";
import Welcome from "@DashboardApp/pages/welcome/Welcome";
import BrowserExtensions from "./pages/browser-extensions/BrowserExtensions";
import Settings from './pages/settings/Settings';
import { useSelector } from "react-redux";

function SettingsRoute() {
  const query = new URLSearchParams(useLocation().search);
  const page = query.get("page");
  const path = query.get("path");
  const currentEvent = query.get("event");
  const navStatus = useSelector((state) => state);

  const temporaryLoginStatus = navStatus.dmTemporaryLogin;
  const browserExtensionStatus = navStatus.dmBrowserExtension;

  let routePage = <p> Login Me Now Dashboard is Loading... </p>;

  if (lmn_admin.home_slug === page) {
    if ("getting-started" === currentEvent) {
      routePage = <Settings />;
    } else {
      switch (path) {
        case "browser-extensions":
          if(browserExtensionStatus){
            routePage = <BrowserExtensions />;
          }
          break;
        case "temporary-login":
          if(temporaryLoginStatus){
            routePage = <Welcome />;
          }
          break;
        case 'settings':
              routePage = <Settings />
            break;

        default:
          routePage = <Settings />;
          break;
      }
    }

    astWpMenuClassChange(path);
  }

  return <>{routePage}</>;
}

export default SettingsRoute;
