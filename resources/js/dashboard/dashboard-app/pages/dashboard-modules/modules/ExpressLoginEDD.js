import React, { useState } from "react";
import { Switch } from "@headlessui/react";
import { useDispatch, useSelector } from "react-redux";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

function ExpressLoginEDD({ colorChange, isAvailable }) {
  const isProAvailable = lmn_admin.pro_available ? true : false;
  const dispatch = useDispatch();
  const [hover, setHover] = useState(false);

  const handleMouseEnter = () => {
    setHover(true);
  };
  const handleMouseLeave = () => {
    setHover(false);
  };

  const enableDmExpressLoginEDD = useSelector(
    (state) => state.dmExpressLoginEDD
  );

  const handleDmExpressLoginEDD = () => {
    let assetStatus;
    if (
      enableDmExpressLoginEDD === false ||
      enableDmExpressLoginEDD === undefined
    ) {
      assetStatus = true;
    } else {
      assetStatus = false;
    }

    dispatch({
      type: "ENABLE_DM_EXPRESS_LOGIN_EDD",
      payload: assetStatus,
    });

    const formData = new window.FormData();

    formData.append("action", "login_me_now_update_admin_setting");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "dm_express_login_edd");
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
                d="M15.0006 2.5C18.4518 2.5 21.5773 3.89885 23.8386 6.16091C26.1007 8.42296 27.5 11.5485 27.5 15.0001C27.5 18.4518 26.1012 21.5768 23.8391 23.8393C21.5778 26.1007 18.4522 27.5002 15.0006 27.5002C11.5485 27.5002 8.42296 26.1019 6.16091 23.8393C3.89885 21.5773 2.5 18.4518 2.5 15.0001C2.5 11.5485 3.89885 8.42296 6.16138 6.16091C8.42344 3.89885 11.5485 2.5 15.0006 2.5ZM15.7149 16.9735H14.3784L14.3774 17.9355L14.2347 17.9728C13.9184 18.0662 13.6472 18.1966 13.4207 18.3672C13.1884 18.5427 13.0077 18.7501 12.8829 18.9879C12.7567 19.2273 12.6927 19.4928 12.6901 19.7776C12.6918 20.1121 12.7804 20.4102 12.9553 20.6678C13.1216 20.913 13.3549 21.123 13.6522 21.3C13.8622 21.4251 14.1008 21.5366 14.3677 21.6349L14.644 21.7288C14.895 21.8092 15.0985 21.8926 15.2483 21.9754C15.3809 22.0482 15.4698 22.1219 15.5179 22.1918C15.5624 22.2551 15.5834 22.3248 15.5825 22.4095C15.5825 22.5065 15.5572 22.5824 15.5046 22.6513C15.4501 22.7223 15.3621 22.7831 15.2362 22.8301C15.0981 22.8822 14.9195 22.9101 14.6986 22.912C14.517 22.9107 14.336 22.8948 14.1589 22.8644C13.9816 22.8324 13.817 22.7913 13.6685 22.7414C13.5135 22.6899 13.3836 22.6359 13.2744 22.5778L12.9678 22.4146L12.5286 23.8416L12.7334 23.954C12.8741 24.0313 13.0438 24.0986 13.2478 24.1594C13.4451 24.2187 13.662 24.2659 13.8998 24.3019L14.1353 24.3309L14.3774 24.3485L14.3784 25.2516H15.7149L15.7149 24.2246L15.7862 24.2055C16.1283 24.1025 16.4147 23.9603 16.6455 23.7757C16.8789 23.5919 17.0576 23.373 17.1772 23.1228C17.2949 22.8752 17.3537 22.6126 17.3537 22.3357C17.3537 22.0099 17.2818 21.7176 17.1348 21.4643C16.9882 21.2119 16.7738 20.995 16.4933 20.8119C16.2787 20.6725 16.0233 20.5468 15.7285 20.4353L15.5 20.3543C15.2216 20.2584 14.9994 20.17 14.8317 20.0881C14.6856 20.017 14.5827 19.9424 14.5247 19.8722C14.4818 19.8208 14.4613 19.7601 14.4613 19.6775C14.4613 19.62 14.4783 19.5682 14.5175 19.5136C14.5599 19.4535 14.6324 19.3993 14.7404 19.3538C14.8631 19.3021 15.0284 19.2735 15.2388 19.2725C15.4173 19.2734 15.5854 19.2876 15.7388 19.3142C15.8875 19.3412 16.0202 19.3739 16.1318 19.4108C16.2442 19.4488 16.3368 19.4851 16.4078 19.5186L16.7005 19.6567L17.1579 18.2772L16.9364 18.166C16.7455 18.0701 16.5134 17.9928 16.2366 17.9314L16.0558 17.8968L15.8627 17.8701L15.7149 17.8563L15.7149 16.9735ZM15.0006 4.24642C9.09596 4.24642 4.30308 9.00526 4.24785 14.8963L9.17857 9.9656L10.8321 11.6192L7.22385 15.2275H22.7771L19.1688 11.6192L20.8224 9.9656L25.7533 14.8958C25.6972 9.00478 20.9048 4.24642 15.0006 4.24642ZM15.0001 4.75087C15.8452 4.75087 16.537 5.25461 16.537 5.87023V8.24013H19.5818L15.0001 13.048L10.4184 8.24013H13.4632V5.87023C13.4632 5.25508 14.155 4.75087 15.0001 4.75087Z"
                fill="black"
              />
            </svg>
          </div>
          <h1 className="text-[#000000] text-[17px] font-medium text-center mb-5 flex justify-center items-center">
            Express Login - EDD
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
          } items-center px-4 border-t-[1px] border-t-[#cacaca] border-b-[#cacaca]`}
        >
    
            <button
              type="button"
              className={`bg-[#F8FAFC] cursor-auto border ${
                colorChange === true
                  ? "border-[#0DA071]  text-[#0DA071]"
                  : "border-[#cacaca]  text-[#023A2E]"
              } px-6 py-1 text-[14px] rounded-[8px]`}
            >
              Coming soon
            </button>
        </div>
      </div>
    </div>
  );
}

export default ExpressLoginEDD;
