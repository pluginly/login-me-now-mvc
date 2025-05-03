import React, { useState } from "react";
import { Switch } from "@headlessui/react";
import { useDispatch, useSelector } from "react-redux";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

function ExpressLoginWC({ colorChange, isAvailable }) {
  const isProAvailable = lmn_admin.pro_available ? true : false;
  const dispatch = useDispatch();
  const [hover, setHover] = useState(false);

  const handleMouseEnter = () => {
    setHover(true);
  };
  const handleMouseLeave = () => {
    setHover(false);
  };

  const enableDmExpressLoginWC = useSelector((state) => state.dmExpressLoginWC);

  const handleDmExpressLoginWC = () => {
    let assetStatus;
    if (
      enableDmExpressLoginWC === false ||
      enableDmExpressLoginWC === undefined
    ) {
      assetStatus = true;
    } else {
      assetStatus = false;
    }

    dispatch({
      type: "ENABLE_DM_EXPRESS_LOGIN_WC",
      payload: assetStatus,
    });

    const formData = new window.FormData();

    formData.append("action", "login_me_now_update_admin_setting");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "dm_express_login_wc");
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
    <div className={`mb-8 mx-4 flex`}>
      <div
        class={`relative rounded-[8px] border border-[#cacaca] flex flex-col justify-between bg-[#F8FAFC]`}
        onMouseEnter={!isProAvailable === true ? handleMouseEnter : null}
        onMouseLeave={!isProAvailable === true ? handleMouseLeave : null}
      >
        <div className={`px-8 pt-16 pb-10 text-center responsive-box`}>
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
                fill-rule="evenodd"
                clip-rule="evenodd"
                d="M25 3.75C26.3807 3.75 27.5 4.86929 27.5 6.25V23.75C27.5 25.1307 26.3807 26.25 25 26.25H5C3.61929 26.25 2.5 25.1307 2.5 23.75V6.25C2.5 4.86929 3.61929 3.75 5 3.75H25ZM22.1519 6.25602C21.6599 6.29245 21.2865 6.54641 21.0322 7.05433C19.9802 9.10399 19.2336 12.4235 18.7924 17.0307C18.1477 15.2894 17.6048 13.2396 17.1805 10.8272C16.9939 9.75694 16.5359 9.24919 15.7893 9.30357C15.2802 9.33983 14.856 9.70256 14.5165 10.3919L10.8008 17.9559C10.19 15.3258 9.61313 12.1151 9.08715 8.32397C8.96845 7.38063 8.47638 6.94539 7.61094 7.01808C7.13582 7.05433 6.77957 7.23561 6.54201 7.58039C6.30446 7.90669 6.20272 8.32397 6.27071 8.79564C7.27164 15.5977 8.20491 20.187 9.07019 22.5633C9.40949 23.4338 9.79965 23.8511 10.2578 23.8148C10.9705 23.7605 11.8188 22.7083 12.8199 20.6585C13.3459 19.4976 14.1603 17.7563 15.2633 15.4346C16.1794 18.8628 17.4351 21.4385 19.013 23.1619C19.4542 23.6517 19.9122 23.8692 20.3534 23.833C20.7437 23.7967 21.0489 23.579 21.2528 23.18C21.4223 22.8352 21.4903 22.4361 21.4562 21.9829C21.3545 20.3322 21.5073 18.0284 21.9313 15.0717C22.3724 12.0245 22.9155 9.82962 23.5773 8.52355C23.7129 8.25146 23.7638 7.97937 23.7469 7.6529C23.7129 7.23561 23.5432 6.89101 23.2209 6.61892C22.8985 6.34683 22.5423 6.21993 22.1519 6.25602Z"
                fill="#683FA6"
              />
            </svg>
          </div>
          <h1 className="text-[#000000] text-[17px] font-medium text-center mb-5 flex justify-center items-center">
            Express Login - WC
          </h1>
          <p className="text-[#6B6D71] text-[14px] text-center leading-[1.9]">
            Customers can log in to their accounts using the secret link
            associated with their username, no need to remember the username &
            password.
          </p>
        </div>
        <div
          className={`bg-[#F0F2F4] py-3 rounded-b-[8px] flex ${
            isAvailable === true ? "justify-between" : "justify-center"
          } items-center px-4 border-t-[1px] border-t-[#cacaca] border-b-[#cacaca]}`}
        >
         
            <button
              type="button"
              className={`bg-[#F8FAFC] border px-6 py-1 text-[14px] rounded-[8px] cursor-auto`}
            >
              Coming soon
            </button>

        </div>
        
      </div>
    </div>
  );
}

export default ExpressLoginWC;
