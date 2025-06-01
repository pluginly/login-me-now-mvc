import { __ } from "@wordpress/i18n";
import apiFetch from "@wordpress/api-fetch";
import { useDispatch } from "react-redux";
import TemporaryLoginPopup from "./TemporaryLoginPopup";
import Table from "./Table";
import NeedSupport from "../components/NeedSupport";
import Title from "../components/Title";
import SubTitle from "../components/SubTitle";
import { useState } from "react";
import { Tooltip } from "antd";

const classNames = (...classes) => classes.filter(Boolean).join(" ");

const TemporaryLogin = () => {
  const dispatch = useDispatch();
  const [setTime, setSetTime] = useState();
  // datetime-local previous date disabled
  const getCurrentDateTime = () => {
    const now = new Date();
    const year = now.getFullYear();
    const month = `${now.getMonth() + 1}`.padStart(2, "0");
    const day = `${now.getDate()}`.padStart(2, "0");
    const hours = `${now.getHours()}`.padStart(2, "0");
    const minutes = `${now.getMinutes()}`.padStart(2, "0");

    return `${year}-${month}-${day}T${hours}:${minutes}`;
  };

  const handleSetTime = (e) => {
    e.preventDefault();
    setSetTime(e.target.value);
  };

  // generate token operation
  const onGenerateLoginLink = (e) => {
    e.preventDefault();
    e.stopPropagation();
    const setGMTTime = new Date(setTime);
    const formData = new window.FormData();
    formData.append("expiration", setGMTTime);

    apiFetch({
      url: lmn_admin.rest_args.root + "/temporary-login/generate",
      method: "POST",
      body: formData,
    })
      .then((data) => {
        if (data.success) {
          dispatch({
            type: "GENERATE_MAGIC_LINK_POPUP",
            payload: { ...data.data },
          });
        }
      })
      .catch((error) => {
        console.log(error);
      });
  };

  return (
    <>
      <TemporaryLoginPopup />
      <main className="py-[2.43rem]">
        <div className="max-w-3xl mx-auto px-6 lg:max-w-screen-2xl">
          <div className="grid grid-cols-1 gap-4 items-start lg:grid-cols-12 rounded-md bg-white overflow-hidden shadow-sm p-12">
            <div className="grid grid-cols-1 lg:col-span-7 gap-4 h-full">
              <section aria-labelledby="section-1-title h-full">
                <div className="flex flex-col justify-center h-full">
                  <div className="">
                    <Title text="Share dashboard access securly" />
                    <SubTitle text="Create self-expiring login link to grant temporary access to an individual (developer, support stuff or any outsider). No password is needed, just generate the login link & share it with the concerned person!" />
                    <form
                      onSubmit={onGenerateLoginLink}
                      className="inline-flex flex-nowrap items-end"
                    >
                      <span className="relative z-0 inline-flex flex-col sm:flex-row justify-start w-full">
                        <div>
                          <label htmlFor="expiration"  className="text-[16px] text-[#1E293B] font-bold">
                            Set expiration time{" "}
                            <Tooltip title="When will this link expire & no loner be accessible">
                              <span>
                                <svg
                                  className="inline-block"
                                  fill="#9A9A9A"
                                  width="14px"
                                  height="14px"
                                  viewBox="0 0 24 24"
                                  xmlns="http://www.w3.org/2000/svg"
                                >
                                  <path d="M12,1A11,11,0,1,0,23,12,11.013,11.013,0,0,0,12,1Zm0,20a9,9,0,1,1,9-9A9.011,9.011,0,0,1,12,21ZM13,8H11V6h2Zm0,10H11V10h2Z" />
                                </svg>
                              </span>
                            </Tooltip>
                          </label>
                          <input
                            className="mt-2 !p-2 input-padding w-full"
                            type="datetime-local"
                            name="expiration"
                            id="expiration"
                            min={getCurrentDateTime()}
                            onChange={handleSetTime}
                            required
                          />
                        </div>
                      </span>
                      <span className="ml-2 relative z-0 inline-flex flex-col sm:flex-row justify-start w-full">
                        <div>
                          <button
                            type="submit"
                            className="sm:inline-flex items-center p-[14px] border border-transparent text-[12px] font-medium rounded-md shadow-sm text-white bg-lmn focus-visible:bg-lmn-hover hover:bg-lmn-hover focus:outline-none mr-4 mb-2 sm:mb-0"
                          >
                            {__("Generate login link", "login-me-now")}
                          </button>
                        </div>
                      </span>
                    </form>
                  </div>
                </div>
              </section>
            </div>
          </div>

          <div className="grid grid-cols-1 gap-[32px] items-start lg:grid-cols-3 lg:gap-[32px] xl:gap-[32px] mt-[32px]">
            {/* Left column */}
            <div className={classNames( 'lg:col-span-2 grid grid-cols-1 gap-[32px]')}  >
              <Table />
            </div>
            {/* Right Column */}
            <div className="grid grid-cols-1 gap-[32px]">
              <NeedSupport
                classNames="rounded-md border border-[#9F9F9F]"
                title="Share Your Feedback"
                buttonText="Submit a review "
                icon={
                  <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                  >
                    <path
                      d="M19.5 4.5H12.5V6H16.94L10.97 11.97L12.03 13.03L18 7.06V11.5H19.5V4.5ZM6.5 5.5C5.96957 5.5 5.46086 5.71071 5.08579 6.08579C4.71071 6.46086 4.5 6.96957 4.5 7.5V17.5C4.5 18.0304 4.71071 18.5391 5.08579 18.9142C5.46086 19.2893 5.96957 19.5 6.5 19.5H16.5C17.0304 19.5 17.5391 19.2893 17.9142 18.9142C18.2893 18.5391 18.5 18.0304 18.5 17.5V14.5H17V17.5C17 17.6326 16.9473 17.7598 16.8536 17.8536C16.7598 17.9473 16.6326 18 16.5 18H6.5C6.36739 18 6.24021 17.9473 6.14645 17.8536C6.05268 17.7598 6 17.6326 6 17.5V7.5C6 7.36739 6.05268 7.24021 6.14645 7.14645C6.24021 7.05268 6.36739 7 6.5 7H9.5V5.5H6.5Z"
                      fill="#007CBA"
                    />
                  </svg>
                }
                subtitle="If you find this plugin helpful, kindly consider leaving a review on WordPress.org. Your valuable feedback is highly appreciated and helps others discover the plugin."
                link="https://wordpress.org/support/plugin/login-me-now/reviews/?rate=5#new-post"
              />
              <NeedSupport
                classNames=""
                title="Need Support?"
                subtitle="Whether you need help or have a new feature request, please create a topic in the support forum on WordPress.org."
                buttonText="Support forum "
                link="https://wordpress.org/support/plugin/login-me-now/"
              />
            </div>
          </div>
        </div>
      </main>
    </>
  );
};

export default TemporaryLogin;
