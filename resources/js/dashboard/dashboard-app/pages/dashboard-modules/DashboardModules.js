import React, { useState } from "react";
import { __ } from "@wordpress/i18n";
import TickIcon from "./TickIcon";
import Module from "./Module";
import TemporaryLogin from "./modules/TemporaryLogin";
import AdvanceShare from "./modules/AdvanceShare";
import SocialLogin from "./modules/SocialLogin";
import ExpressLoginWC from "./modules/ExpressLoginWC";
import ExpressLoginEDD from "./modules/ExpressLoginEDD";
import ExpressLoginEmail from "./modules/ExpressLoginEmail";
import OTPLogin from "./modules/OTPLogin";
import ActivityLog from "./modules/ActivityLog";
import BrowserExtension from "./modules/BrowserExtension";
import UserSwitching from "./modules/UserSwitching";
import ConditionalLogin from "./modules/ConditionalLogin";

function DashboardModules() {
  const isProAvailable = lmn_admin.pro_available ? true : false;
  return (
    <main className="py-[2.43rem]">
      <div className="max-w-3xl mx-auto px-6 lg:max-w-screen-2xl">
        <div className="gap-4 items-start lg:grid-cols-5 lg:gap-0 xl:gap-0 rounded-md bg-white overflow-hidden shadow-sm pt-4 px-4">
          <div className="">
            <section aria-labelledby="section-1-title h-full">
              <div className="pb-4 px-4 mt-4">
                <h3 className="p-0 flex-1 justify-right inline-flex text-xl leading-6 font-semibold text-slate-800">
                  {__("Modules", "login-me-now")}
                </h3>
              </div>

              <div class="w-fullpx-4 grid sm:grid-cols-2 large-grid extralarge-grid md:grid-cols-3">
                <div
                  class={`mx-4 bg-[#023A2E] rounded-[8px] flex ${
                    isProAvailable === true
                      ? "justify-center"
                      : "justify-between"
                  } flex-col mb-8 `}
                >
                  <div className="px-8 pt-16 pb-10 text-center responsive-box">
                    {isProAvailable === true ? (
                      <div className="flex flex-col items-center justify-start">
                        <svg
                          className="mb-4"
                          width="64"
                          height="64"
                          viewBox="0 0 128 128"
                          fill="none"
                          xmlns="http://www.w3.org/2000/svg"
                        >
                          <g filter="url(#filter0_d_1525_6)">
                            <path
                              opacity="0.2"
                              d="M29.4453 77.4454L50.5553 98.5554L25.2403 107.755C24.5424 108.012 23.7856 108.064 23.0593 107.904C22.333 107.744 21.6676 107.38 21.1418 106.854C20.616 106.328 20.2516 105.663 20.0919 104.936C19.9322 104.21 19.9837 103.453 20.2403 102.755L29.4453 77.4454ZM98.8553 75.0854L52.9153 29.1454C52.452 28.6822 51.8794 28.3434 51.2504 28.1602C50.6214 27.977 49.9563 27.9555 49.3168 28.0976C48.6773 28.2396 48.0839 28.5407 47.5916 28.973C47.0993 29.4052 46.7239 29.9546 46.5003 30.5704L39.0503 51.0704L76.9603 88.9804L97.4603 81.5304C98.0795 81.3056 98.6316 80.9274 99.0649 80.4312C99.4982 79.9351 99.7986 79.3371 99.938 78.6932C100.077 78.0494 100.051 77.3807 99.8618 76.7498C99.6724 76.1188 99.3262 75.5462 98.8553 75.0854Z"
                              fill="#D9F5EC"
                            />
                            <path
                              d="M55.7454 26.315C54.8059 25.378 53.6456 24.6927 52.3714 24.3224C51.0973 23.9521 49.7504 23.9086 48.455 24.1961C47.1597 24.4836 45.9576 25.0927 44.9597 25.9672C43.9618 26.8418 43.2003 27.9536 42.7454 29.2L16.5004 101.39C16.0692 102.58 15.93 103.857 16.0943 105.112C16.2586 106.368 16.7217 107.565 17.4446 108.605C18.1676 109.644 19.1294 110.495 20.2492 111.086C21.3691 111.677 22.6143 111.99 23.8804 112C24.8126 111.994 25.7365 111.824 26.6104 111.5L98.7954 85.25C100.042 84.7956 101.155 84.0345 102.03 83.0369C102.905 82.0393 103.514 80.8373 103.802 79.5418C104.09 78.2464 104.047 76.8993 103.677 75.6249C103.307 74.3505 102.622 73.1898 101.685 72.25L55.7454 26.315ZM51.5804 93.92L34.0804 76.42L40.6604 58.315L69.6854 87.34L51.5804 93.92ZM24.0804 103.92L31.0804 84.715L43.3054 96.94L24.0804 103.92ZM78.0004 84.32L43.6804 50L50.1804 32.065L95.8954 77.78L78.0004 84.32ZM80.0004 36C80.0755 33.2959 80.7302 30.6393 81.9204 28.21C84.5704 22.915 89.5704 20 96.0004 20C99.3504 20 101.5 18.855 102.825 16.395C103.523 15.0187 103.923 13.5111 104 11.97C104.004 10.9091 104.43 9.8933 105.183 9.14597C105.936 8.39863 106.955 7.98102 108.015 7.985C109.076 7.98898 110.092 8.41422 110.839 9.16718C111.587 9.92014 112.004 10.9391 112 12C112 18.43 107.74 28 96.0004 28C92.6504 28 90.5004 29.145 89.1754 31.605C88.4777 32.9813 88.0774 34.4889 88.0004 36.03C87.9984 36.5553 87.893 37.075 87.6902 37.5596C87.4873 38.0441 87.191 38.484 86.8182 38.854C86.4454 39.2241 86.0033 39.5171 85.5173 39.7163C85.0312 39.9155 84.5107 40.017 83.9854 40.015C83.4601 40.013 82.9403 39.9076 82.4558 39.7048C81.9712 39.5019 81.5314 39.2056 81.1613 38.8328C80.7913 38.46 80.4983 38.0179 80.2991 37.5319C80.0999 37.0458 79.9984 36.5253 80.0004 36ZM68.0004 20V8C68.0004 6.93913 68.4218 5.92172 69.1719 5.17157C69.9221 4.42143 70.9395 4 72.0004 4C73.0612 4 74.0787 4.42143 74.8288 5.17157C75.579 5.92172 76.0004 6.93913 76.0004 8V20C76.0004 21.0609 75.579 22.0783 74.8288 22.8284C74.0787 23.5786 73.0612 24 72.0004 24C70.9395 24 69.9221 23.5786 69.1719 22.8284C68.4218 22.0783 68.0004 21.0609 68.0004 20ZM118.83 61.17C119.202 61.5416 119.496 61.9828 119.697 62.4682C119.898 62.9537 120.001 63.4739 120.001 63.9993C120.001 64.5246 119.897 65.0448 119.696 65.53C119.494 66.0153 119.2 66.4562 118.828 66.8275C118.456 67.1988 118.015 67.4933 117.53 67.6941C117.044 67.8949 116.524 67.9982 115.999 67.998C115.473 67.9977 114.953 67.894 114.468 67.6928C113.983 67.4915 113.542 67.1966 113.17 66.825L105.17 58.825C104.42 58.0744 103.998 57.0565 103.998 55.995C103.998 54.9335 104.42 53.9156 105.17 53.165C105.921 52.4144 106.939 51.9928 108 51.9928C109.062 51.9928 110.08 52.4144 110.83 53.165L118.83 61.17ZM121.265 39.795L109.265 43.795C108.259 44.1305 107.16 44.0524 106.211 43.578C105.262 43.1035 104.541 42.2715 104.205 41.265C103.87 40.2585 103.948 39.16 104.422 38.211C104.897 37.2621 105.729 36.5405 106.735 36.205L118.735 32.205C119.742 31.8695 120.84 31.9476 121.789 32.422C122.738 32.8965 123.46 33.7285 123.795 34.735C124.131 35.7415 124.053 36.84 123.578 37.789C123.104 38.7379 122.272 39.4595 121.265 39.795Z"
                              fill="#D9F5EC"
                            />
                          </g>
                          <defs>
                            <filter
                              id="filter0_d_1525_6"
                              x="-4"
                              y="0"
                              width="136"
                              height="136"
                              filterUnits="userSpaceOnUse"
                              color-interpolation-filters="sRGB"
                            >
                              <feFlood
                                flood-opacity="0"
                                result="BackgroundImageFix"
                              />
                              <feColorMatrix
                                in="SourceAlpha"
                                type="matrix"
                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                result="hardAlpha"
                              />
                              <feOffset dy="4" />
                              <feGaussianBlur stdDeviation="2" />
                              <feComposite in2="hardAlpha" operator="out" />
                              <feColorMatrix
                                type="matrix"
                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0"
                              />
                              <feBlend
                                mode="normal"
                                in2="BackgroundImageFix"
                                result="effect1_dropShadow_1525_6"
                              />
                              <feBlend
                                mode="normal"
                                in="SourceGraphic"
                                in2="effect1_dropShadow_1525_6"
                                result="shape"
                              />
                            </filter>
                          </defs>
                        </svg>
                        <h1 className="text-[#1BD8B9] text-[16px] font-medium text-center mb-5">
                          All the pro features are unlocked!
                        </h1>
                      </div>
                    ) : (
                      <>
                        <div className="bg-[#D9F5EC] inline-block py-2.5 px-3 rounded-[8px] mb-4">
                          <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="30"
                            height="30"
                            viewBox="0 0 30 30"
                            fill="none"
                          >
                            <path
                              d="M28.5752 8.92802C28.3637 8.74362 28.1022 8.62624 27.8238 8.59077C27.5455 8.5553 27.2629 8.60334 27.0119 8.7288L21.1279 11.6585L16.2154 3.49872C16.0891 3.28934 15.9109 3.11614 15.698 2.99591C15.4851 2.87568 15.2447 2.8125 15.0002 2.8125C14.7557 2.8125 14.5153 2.87568 14.3024 2.99591C14.0895 3.11614 13.9112 3.28934 13.7849 3.49872L8.87244 11.662L2.9908 8.73232C2.74042 8.60797 2.45889 8.56034 2.18154 8.59541C1.90419 8.63049 1.64338 8.74669 1.43183 8.92945C1.22028 9.11221 1.06742 9.35337 0.992434 9.62269C0.917448 9.892 0.923675 10.1775 1.01033 10.4433L5.34627 23.7276C5.38987 23.8611 5.46288 23.9831 5.55992 24.0845C5.65696 24.186 5.77555 24.2643 5.90694 24.3138C6.03832 24.3633 6.17914 24.3827 6.31901 24.3705C6.45887 24.3583 6.59421 24.3148 6.71502 24.2433C6.74432 24.2257 9.7408 22.4995 15.0002 22.4995C20.2596 22.4995 23.256 24.2257 23.283 24.2421C23.4038 24.3143 23.5394 24.3584 23.6796 24.371C23.8198 24.3837 23.9611 24.3646 24.0929 24.3151C24.2248 24.2657 24.3438 24.1872 24.4411 24.0855C24.5385 23.9838 24.6117 23.8615 24.6553 23.7276L28.9912 10.4468C29.0803 10.1809 29.0884 9.89454 29.0143 9.62409C28.9402 9.35364 28.7874 9.11134 28.5752 8.92802ZM23.2033 22.128C21.7971 21.5315 18.9939 20.6245 15.0002 20.6245C11.0064 20.6245 8.2033 21.5315 6.79705 22.128L3.13026 10.8979L8.4119 13.5311C8.73159 13.6888 9.09874 13.721 9.44101 13.6215C9.78328 13.5219 10.0759 13.2978 10.2611 12.9933L15.0002 5.11591L19.7392 12.9909C19.9247 13.295 20.217 13.5187 20.559 13.6182C20.9009 13.7177 21.2677 13.6858 21.5873 13.5288L26.8701 10.8979L23.2033 22.128ZM20.6099 18.378C20.5717 18.5949 20.4583 18.7914 20.2897 18.933C20.121 19.0747 19.9079 19.1524 19.6877 19.1526C19.6327 19.1526 19.5778 19.1479 19.5236 19.1386C16.5291 18.6245 13.4689 18.6245 10.4744 19.1386C10.2295 19.1818 9.97745 19.1259 9.77372 18.9833C9.56999 18.8407 9.43127 18.6229 9.38807 18.378C9.34487 18.1331 9.40073 17.8811 9.54336 17.6773C9.68599 17.4736 9.9037 17.3349 10.1486 17.2917C13.3587 16.7401 16.6393 16.7401 19.8494 17.2917C20.0938 17.3346 20.3113 17.4727 20.4541 17.6757C20.5968 17.8786 20.6533 18.13 20.6111 18.3745L20.6099 18.378Z"
                              fill="#023A2E"
                            />
                          </svg>
                        </div>

                        <h1 className="text-[#1BD8B9] text-[17px] font-medium text-center mb-5">
                          More powerful options
                        </h1>
                        <div className="flex flex-col flex-wrap content-center">
                          <div className="inline-box-flex inline-size-auto flex">
                            <TickIcon />
                            <span className="ml-3 mb-[12px] text-[#D9F5EC] text-[16px] noto-serif responsive-span">
                              Advanced social login
                            </span>
                          </div>
                          <div className="inline-box-flex inline-size-auto flex">
                            <TickIcon />
                            <span className="ml-3 mb-[12px] text-[#D9F5EC] text-[16px] noto-serif responsive-span">
                              Express login - WC & EDD
                            </span>
                          </div>
                          <div className="inline-box-flex inline-size-auto flex">
                            <TickIcon />
                            <span className="ml-3 mb-[12px] text-[#D9F5EC] text-[16px] noto-serif responsive-span">
                              OTP login
                            </span>
                          </div>
                          <div className="inline-box-flex inline-size-auto flex">
                            <TickIcon />
                            <span className="ml-3 mb-[12px] text-[#D9F5EC] text-[16px] noto-serif responsive-span">
                              24/7 Priority Support
                            </span>
                          </div>
                        </div>
                      </>
                    )}
                  </div>
                  {!isProAvailable && (
                    <div className="bg-black py-3 text-center rounded-b-[8px] border-t-[2px] border-t-[#000000] ">
                      <a
                        href="https://loginmenow.com/pricing/"
                        target="_blank"
                      >
                        <button
                          type="button"
                          className="bg-[#EDEDED] px-6 py-[0.4rem] text-[#023A2E] text-[13px] font-semibold rounded-[8px]"
                        >
                          Upgrade to PRO
                        </button>
                      </a>
                    </div>
                  )}
                </div>
                <TemporaryLogin
                  colorChange={false}
                  proItem={false}
                  none={false}
                />
                <BrowserExtension
                  colorChange={false}
                  proItem={false}
                  none={false}
                />
                <SocialLogin colorChange={false} proItem={false} none={false} />
                {/* <AdvanceShare colorChange={true} proItem={false} none={false} /> */}

                <ExpressLoginWC
                  colorChange={false}
                  proItem={true}
                  none={false}
                  isAvailable={false}
                />
                <ExpressLoginEDD
                  colorChange={false}
                  proItem={true}
                  none={false}
                  isAvailable={false}
                />
                {/* <ExpressLoginEmail
                  colorChange={false}
                  proItem={true}
                  none={false}
                /> */}
                <OTPLogin
                  colorChange={false}
                  proItem={true}
                  none={false}
                  isAvailable={false}
                />
                <ConditionalLogin
                  colorChange={false}
                  proItem={false}
                  none={false}
                  isAvailable={false}
                />
                <UserSwitching
                  colorChange={false}
                  proItem={false}
                  none={false}
                />
                <ActivityLog
                  colorChange={false}
                  proItem={false}
                  none={false}
                  isAvailable={true}
                />
              </div>
            </section>
          </div>
        </div>
      </div>
    </main>
  );
}

export default DashboardModules;
