import React, { useState } from "react";
import { Switch } from "@headlessui/react";
import { useDispatch, useSelector } from "react-redux";
import apiFetch from "@wordpress/api-fetch";
import { __ } from "@wordpress/i18n";

function classNames(...classes) {
  return classes.filter(Boolean).join(" ");
}

function AdvanceShare({ colorChange, proItem }) {
  const dispatch = useDispatch();
  const [isChecked, setIsChecked] = useState(true);
  const [hover, setHover] = useState(false);

  const handleMouseEnter = () => {
    setHover(true);
  };
  const handleMouseLeave = () => {
    setHover(false);
  };

  const enableDmAdvanceShare = useSelector((state) => state.dmAdvanceShare);

  const handleDmAdvanceShare = () => {
    let assetStatus;
    if (enableDmAdvanceShare === false || enableDmAdvanceShare === undefined) {
      assetStatus = true;
    } else {
      assetStatus = false;
    }

    dispatch({
      type: "ENABLE_DM_ADVANCE_SHARE",
      payload: assetStatus,
    });

    const formData = new window.FormData();

    formData.append("action", "login_me_now_update_admin_setting");
    formData.append("security", lmn_admin.update_nonce);
    formData.append("key", "dm_advance_share");
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
        class={`relative rounded-[8px] border border-[#9F9F9F] flex flex-col justify-between ${
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
              <g clip-path="url(#clip0_1333_17)">
                <path
                  d="M22.5 15C23.5352 15 24.5068 15.1953 25.415 15.5859C26.3232 15.9766 27.1191 16.5088 27.8027 17.1826C28.4863 17.8564 29.0234 18.6523 29.4141 19.5703C29.8047 20.4883 30 21.4648 30 22.5C30 23.5352 29.8047 24.5068 29.4141 25.415C29.0234 26.3232 28.4912 27.1191 27.8174 27.8027C27.1436 28.4863 26.3477 29.0234 25.4297 29.4141C24.5117 29.8047 23.5352 30 22.5 30C21.4648 30 20.4932 29.8047 19.585 29.4141C18.6768 29.0234 17.8809 28.4912 17.1973 27.8174C16.5137 27.1436 15.9766 26.3477 15.5859 25.4297C15.1953 24.5117 15 23.5352 15 22.5C15 21.4648 15.1953 20.4932 15.5859 19.585C15.9766 18.6768 16.5088 17.8809 17.1826 17.1973C17.8564 16.5137 18.6523 15.9766 19.5703 15.5859C20.4883 15.1953 21.4648 15 22.5 15ZM22.5 28.125C23.2715 28.125 23.999 27.9785 24.6826 27.6855C25.3662 27.3926 25.9619 26.9922 26.4697 26.4844C26.9775 25.9766 27.3828 25.3809 27.6855 24.6973C27.9883 24.0137 28.1348 23.2812 28.125 22.5C28.125 21.7285 27.9785 21.001 27.6855 20.3174C27.3926 19.6338 26.9922 19.0381 26.4844 18.5303C25.9766 18.0225 25.376 17.6172 24.6826 17.3145C23.9893 17.0117 23.2617 16.8652 22.5 16.875C21.7188 16.875 20.9912 17.0215 20.3174 17.3145C19.6436 17.6074 19.0479 18.0078 18.5303 18.5156C18.0127 19.0234 17.6074 19.624 17.3145 20.3174C17.0215 21.0107 16.875 21.7383 16.875 22.5C16.875 23.2812 17.0215 24.0088 17.3145 24.6826C17.6074 25.3564 18.0078 25.9521 18.5156 26.4697C19.0234 26.9873 19.6191 27.3926 20.3027 27.6855C20.9863 27.9785 21.7188 28.125 22.5 28.125ZM22.5 22.5H25.3125V24.375H20.625V18.75H22.5V22.5ZM17.1387 14.8242C16.8652 15.0195 16.6064 15.2246 16.3623 15.4395C16.1182 15.6543 15.8838 15.8838 15.6592 16.1279C14.9854 15.7666 14.2773 15.4883 13.5352 15.293C12.793 15.0977 12.0312 15 11.25 15C10.3906 15 9.56055 15.1123 8.75977 15.3369C7.95898 15.5615 7.21191 15.874 6.51855 16.2744C5.8252 16.6748 5.19531 17.1631 4.62891 17.7393C4.0625 18.3154 3.57422 18.9502 3.16406 19.6436C2.75391 20.3369 2.43652 21.084 2.21191 21.8848C1.9873 22.6855 1.875 23.5156 1.875 24.375H0C0 23.2031 0.170898 22.0752 0.512695 20.9912C0.854492 19.9072 1.34766 18.9062 1.99219 17.9883C2.63672 17.0703 3.39844 16.2549 4.27734 15.542C5.15625 14.8291 6.15234 14.2676 7.26562 13.8574C6.16211 13.1348 5.30273 12.2266 4.6875 11.1328C4.07227 10.0391 3.75977 8.82812 3.75 7.5C3.75 6.46484 3.94531 5.49316 4.33594 4.58496C4.72656 3.67676 5.25879 2.88086 5.93262 2.19727C6.60645 1.51367 7.40234 0.976562 8.32031 0.585938C9.23828 0.195313 10.2148 0 11.25 0C12.2852 0 13.2568 0.195313 14.165 0.585938C15.0732 0.976562 15.8691 1.50879 16.5527 2.18262C17.2363 2.85645 17.7734 3.65234 18.1641 4.57031C18.5547 5.48828 18.75 6.46484 18.75 7.5C18.75 8.14453 18.6719 8.77441 18.5156 9.38965C18.3594 10.0049 18.125 10.5859 17.8125 11.1328C17.5 11.6797 17.1289 12.1875 16.6992 12.6562C16.2695 13.125 15.7764 13.5254 15.2197 13.8574C15.9033 14.1309 16.543 14.4531 17.1387 14.8242ZM5.625 7.5C5.625 8.28125 5.77148 9.00879 6.06445 9.68262C6.35742 10.3564 6.75781 10.9521 7.26562 11.4697C7.77344 11.9873 8.36914 12.3926 9.05273 12.6855C9.73633 12.9785 10.4688 13.125 11.25 13.125C12.0215 13.125 12.749 12.9785 13.4326 12.6855C14.1162 12.3926 14.7119 11.9922 15.2197 11.4844C15.7275 10.9766 16.1328 10.3809 16.4355 9.69727C16.7383 9.01367 16.8848 8.28125 16.875 7.5C16.875 6.72852 16.7285 6.00098 16.4355 5.31738C16.1426 4.63379 15.7422 4.03809 15.2344 3.53027C14.7266 3.02246 14.126 2.61719 13.4326 2.31445C12.7393 2.01172 12.0117 1.86523 11.25 1.875C10.4688 1.875 9.74121 2.02148 9.06738 2.31445C8.39355 2.60742 7.79785 3.00781 7.28027 3.51562C6.7627 4.02344 6.35742 4.62402 6.06445 5.31738C5.77148 6.01074 5.625 6.73828 5.625 7.5Z"
                  fill="#023A2E"
                />
              </g>
              <defs>
                <clipPath id="clip0_1333_17">
                  <rect width="30" height="30" fill="white" />
                </clipPath>
              </defs>
            </svg>
          </div>
          <h1 className="text-[#000000] text-[17px] font-medium text-center mb-5">
            Advance share
            {proItem && (
              <span className="bg-[#0DA071] text-[#ffffff] px-2 py-0.5 text-[8px] rounded-[4px] ml-1.5">
                Pro
              </span>
            )}
          </h1>
          <p className="text-[#6B6D71] text-[14px] text-center leading-[1.9]">
            Redirect non-existent content easily with 301 and 302 status code.
            This can help improve your site ranking. Also supports many
          </p>
        </div>
        <div
          className={`bg-[#F0F2F4] py-3 rounded-b-[8px] flex justify-between items-center px-4 border-t-[2px] border-b-[#9F9F9F] ${
            hover && "invisible"
          }`}
        >
          <button
            type="button"
            className={`bg-[#F8FAFC] border ${
              colorChange === true
                ? "border-[#0DA071]  text-[#0DA071]"
                : "border-[#9F9F9F]  text-[#6B6D71]"
            } px-2 py-1 text-[14px] rounded-[8px]`}
          >
            Upcoming
          </button>
          <span title="Upcoming">
            <Switch
              onChange={handleDmAdvanceShare}
              className={classNames(
                enableDmAdvanceShare ? "bg-lmn" : "bg-slate-200",
                "group relative inline-flex h-[8px] w-[32px] flex-shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-lmn focus:ring-offset-2 pointer-events-none"
              )}
            >
              <span
                aria-hidden="true"
                className="pointer-events-none absolute h-full w-full rounded-md bg-white"
              />
              <span
                aria-hidden="true"
                className={classNames(
                  enableDmAdvanceShare ? "bg-lmn" : "bg-gray-200",
                  "pointer-events-none absolute mx-auto h-[16px] w-[32px] rounded-full transition-colors duration-200 ease-in-out"
                )}
              />
              <span
                aria-hidden="true"
                className={classNames(
                  enableDmAdvanceShare ? "translate-x-5" : "translate-x-0",
                  "toggle-bubble pointer-events-none absolute left-0 inline-block h-[16px] w-[16px] transform rounded-full border border-gray-200 bg-white shadow ring-0 transition-transform duration-200 ease-in-out"
                )}
              />
            </Switch>
          </span>
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

export default AdvanceShare;
