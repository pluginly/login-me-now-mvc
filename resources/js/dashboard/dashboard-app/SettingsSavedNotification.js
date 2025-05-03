import { Fragment, useEffect } from 'react'
import { Transition } from '@headlessui/react';
import { CheckCircleIcon } from '@heroicons/react/outline'
import { useLocation } from "react-router-dom";
import { XIcon } from '@heroicons/react/solid'
import { useSelector, useDispatch } from 'react-redux';
import { __ } from '@wordpress/i18n';

export default function SettingsSavedNotification() {

	const dispatch = useDispatch();

	const settingsSavedNotification = useSelector( ( state ) => state.settingsSavedNotification );

	const query = new URLSearchParams( useLocation()?.search );
	const status = query.get("status") ? query.get("status") : "";

	if ( '' !== status && 'imported' === status ) {
		dispatch( {type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: __( 'Successfully imported!', 'login-me-now' ) } );
		setTimeout( ()=>{
			window.location = lmn_admin.login_me_now_base_url;
		}, 1000 );
	}

	useEffect( () => {
		if ( '' !== settingsSavedNotification ) {
			setTimeout( ()=>{
			dispatch( {type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: '' } );
			}, 2000 );
		}
	}, [settingsSavedNotification] );

	return (
		<>
			{ /* Global notification live region, render this permanently at the end of the document */ }
			<div aria-live="assertive" className="fixed inset-10 z-10 flex px-4 py-6 pointer-events-none sm:p-6 sm:items-start lg:mt-[60px] md:mt-[90px]">
				<div className="w-full flex flex-col items-center space-y-4 sm:items-end">
					{/* Notification panel, dynamically insert this into the live region when it needs to be displayed */}
					<Transition
						show={ '' === settingsSavedNotification ? false : true }
						as={Fragment}
						enter="transform ease-out duration-300 transition"
						enterFrom="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
						enterTo="translate-y-0 opacity-100 sm:translate-x-0"
						leave="transition ease-in duration-100"
						leaveFrom="opacity-100"
						leaveTo="opacity-0"
					>
						<div className="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden">
							<div className="p-4">
								<div className="flex items-start">
									<div className="flex-shrink-0">
										<CheckCircleIcon className="h-6 w-6 text-green-400" aria-hidden="true" />
									</div>
									<div className="ml-3 w-0 flex-1 pt-0.5">
										<p className="text-[16px] font-medium text-gray-900">{ settingsSavedNotification }</p>
									</div>
									<div className="ml-4 flex-shrink-0 flex">
										<button
											className="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
											onClick={() => {
												dispatch( {type: 'UPDATE_SETTINGS_SAVED_NOTIFICATION', payload: '' } );
											}}
										>
											<span className="sr-only"> { __( 'Close ', 'login-me-now' ) } </span>
											<XIcon className="h-5 w-5" aria-hidden="true" />
										</button>
									</div>
								</div>
							</div>
						</div>
					</Transition>
				</div>
			</div>
		</>
	)
}
