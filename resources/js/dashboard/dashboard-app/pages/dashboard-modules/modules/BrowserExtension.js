import React, { useState } from "react";
import { Switch } from "@headlessui/react";
import { useDispatch, useSelector } from "react-redux";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";
import { RedirectUrl } from "../../components/RedirectUrl";
import { Link } from "react-router-dom/cjs/react-router-dom.min";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

function BrowserExtension({ colorChange, proItem }) {
  const dispatch = useDispatch();
  const [hover, setHover] = useState(false);

  const handleMouseEnter = () => {
    setHover(true);
  };
  const handleMouseLeave = () => {
    setHover(false);
  };

  const enableDmBrowserExtension = useSelector(
    (state) => state.dmBrowserExtension
  );

  const handleDmBrowserExtension = () => {
    let assetStatus;
    if (
      enableDmBrowserExtension === false ||
      enableDmBrowserExtension === undefined
    ) {
      assetStatus = true;
    } else {
      assetStatus = false;
    }

    dispatch({
      type: "ENABLE_DM_BROWSER_EXTENSION",
      payload: assetStatus,
    });

    const formData = new window.FormData();

    formData.append("action", "login_me_now_update_admin_setting");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "browser_extension");
    formData.append("value", assetStatus);

    apiFetch({
      url: lmn_admin.ajax_url,
      method: "POST",
      body: formData,
    }).then(() => {
      dispatch({
        type: "UPDATE_SETTINGS_SAVED_NOTIFICATION",
        payload: __("Successfully saved!", "login-me-now"),
      });
    });
  };

  return (
    <div className="mb-8 mx-4 flex">
      <div
        class={`relative rounded-[8px] border border-[#cacaca] flex flex-col justify-between ${
          hover === true ? "bg-[#0da071b0]" : "bg-[#F8FAFC]"
        }`}
        onMouseEnter={proItem === true ? handleMouseEnter : null}
        onMouseLeave={proItem === true ? handleMouseLeave : null}
      >
        <div className={`px-8 pt-16 pb-10 text-center responsive-box ${hover && "invisible"}`}>
          <div
            className="bg-[#FFFFFF] border-[1px] border-[#DFDFDF] inline-block py-2.5 px-3 rounded-[8px] mb-4"
          >
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="28"
              height="28"
              viewBox="0 0 28 28"
              fill="none"
            >
              <path
                d="M23.2379 13.4198H21.6172C21.5861 13.4198 21.5563 13.4074 21.5343 13.3855C21.5123 13.3635 21.5 13.3337 21.5 13.3026V8.77682C21.5 8.17293 21.2601 7.59378 20.8331 7.16677C20.4061 6.73975 19.8269 6.49986 19.223 6.49986H14.6973C14.6662 6.49986 14.6364 6.48752 14.6144 6.46554C14.5924 6.44356 14.5801 6.41375 14.5801 6.38267V4.76197C14.5801 3.17174 13.3203 1.84049 11.7301 1.81295C11.3448 1.80618 10.962 1.87621 10.6041 2.01895C10.2462 2.16169 9.92023 2.37428 9.64533 2.64432C9.37044 2.91436 9.15207 3.23645 9.00298 3.59178C8.85389 3.94711 8.77705 4.32858 8.77695 4.71392V6.38267C8.77695 6.41375 8.76461 6.44356 8.74263 6.46554C8.72065 6.48752 8.69085 6.49986 8.65977 6.49986H4.13398C3.51886 6.50172 2.92946 6.7469 2.4945 7.18186C2.05953 7.61682 1.81435 8.20622 1.8125 8.82135V12.9452C1.8125 12.9763 1.82485 13.0061 1.84682 13.028C1.8688 13.05 1.89861 13.0624 1.92969 13.0624H3.50645C5.22734 13.0624 6.65176 14.5553 6.67578 16.2762C6.70039 18.0264 5.29824 19.6249 3.55332 19.6249H1.92969C1.89861 19.6249 1.8688 19.6372 1.84682 19.6592C1.82485 19.6812 1.8125 19.711 1.8125 19.742V23.8659C1.81435 24.481 2.05953 25.0704 2.4945 25.5054C2.92946 25.9403 3.51886 26.1855 4.13398 26.1874H8.25781C8.28889 26.1874 8.3187 26.175 8.34068 26.153C8.36265 26.1311 8.375 26.1013 8.375 26.0702V24.8438C8.375 23.0696 9.8252 21.5421 11.5977 21.5004C13.3613 21.4594 14.9375 22.6905 14.9375 24.4465V26.0702C14.9375 26.1013 14.9498 26.1311 14.9718 26.153C14.9938 26.175 15.0236 26.1874 15.0547 26.1874H19.223C19.8269 26.1874 20.4061 25.9475 20.8331 25.5205C21.2601 25.0934 21.5 24.5143 21.5 23.9104V19.3401C21.5 19.309 21.5123 19.2792 21.5343 19.2572C21.5563 19.2353 21.5861 19.2229 21.6172 19.2229H23.2859C24.9049 19.2229 26.1875 17.8946 26.1875 16.2698C26.1875 14.645 24.8281 13.4198 23.2379 13.4198Z"
                stroke="#023A2E"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </div>
          <h1 className="text-[#000000] text-[17px] font-medium text-center mb-5">
            Browser Extension
          </h1>
          <p className="text-[#6B6D71] text-[14px] text-center leading-[1.9]">
            If frequent logins to the dashboard are necessary throughout the
            day, the browser extension comes in handy.It just takes 1 click to
            login to dashboard.
          </p>
        </div>
        
        <button
          className={`bg-white px-6 py-3 text-[18px] font-semibold text-[#0DA071] rounded-[8px] absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 ${
            !hover && "invisible"
          }`}
        >
          Buy Pro
        </button>
      </div>
    </div>
  );
}

export default BrowserExtension;
