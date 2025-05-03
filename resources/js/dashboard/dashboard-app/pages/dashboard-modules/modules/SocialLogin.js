import React, { useState } from "react";
import { Switch } from "@headlessui/react";
import { useDispatch, useSelector } from "react-redux";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";
import { Link } from "react-router-dom/cjs/react-router-dom.min";
import { RedirectUrl } from "../../components/RedirectUrl";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

function SocialLogin({ colorChange, proItem }) {
  const dispatch = useDispatch();
  const [hover, setHover] = useState(false);

  const handleMouseEnter = () => {
    setHover(true);
  };
  const handleMouseLeave = () => {
    setHover(false);
  };

  const enableDmSocialLogin = useSelector((state) => state.dmSocialLogin);

  const handleDmSocialLogin = () => {
    let assetStatus;
    if (enableDmSocialLogin === false || enableDmSocialLogin === undefined) {
      assetStatus = true;
    } else {
      assetStatus = false;
    }

    dispatch({
      type: "ENABLE_DM_SOCIAL_LOGIN",
      payload: assetStatus,
    });

    const formData = new window.FormData();

    formData.append("action", "login_me_now_update_admin_setting");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "social_login");
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
              width="30"
              height="30"
              viewBox="0 0 30 30"
              fill="none"
            >
              <path
                d="M22.4774 23.4059C21.604 22.2495 20.474 21.3117 19.1764 20.6663C17.8788 20.021 16.4491 19.6858 14.9999 19.6871C13.5507 19.6858 12.121 20.021 10.8234 20.6663C9.52583 21.3117 8.39582 22.2495 7.5224 23.4059M22.4774 23.4059C24.1818 21.8899 25.3838 19.8916 25.9264 17.676C26.469 15.4604 26.3252 13.1322 25.5142 11.0002C24.7032 8.86822 23.2633 7.0331 21.3854 5.73825C19.5075 4.4434 17.2803 3.75 14.9993 3.75C12.7182 3.75 10.4911 4.4434 8.61315 5.73825C6.73524 7.0331 5.29531 8.86822 4.48431 11.0002C3.67332 13.1322 3.52958 15.4604 4.07217 17.676C4.61475 19.8916 5.81802 21.8899 7.5224 23.4059M22.4774 23.4059C20.4199 25.2411 17.7569 26.2536 14.9999 26.2496C12.2424 26.2539 9.58019 25.2414 7.5224 23.4059M18.7499 12.1871C18.7499 13.1817 18.3548 14.1355 17.6515 14.8388C16.9483 15.542 15.9945 15.9371 14.9999 15.9371C14.0053 15.9371 13.0515 15.542 12.3482 14.8388C11.645 14.1355 11.2499 13.1817 11.2499 12.1871C11.2499 11.1926 11.645 10.2387 12.3482 9.53548C13.0515 8.83222 14.0053 8.43713 14.9999 8.43713C15.9945 8.43713 16.9483 8.83222 17.6515 9.53548C18.3548 10.2387 18.7499 11.1926 18.7499 12.1871Z"
                stroke="#023A2E"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
          </div>
          <h1 className="text-[#000000] text-[17px] font-medium text-center mb-5">
            Social Login
          </h1>
          <p className="text-[#6B6D71] text-[14px] text-center leading-[1.9]">
            Simplify the login process and make it more convenient for users to
            login / register with social login options.
          </p>
        </div>
       
      </div>
    </div>
  );
}

export default SocialLogin;
