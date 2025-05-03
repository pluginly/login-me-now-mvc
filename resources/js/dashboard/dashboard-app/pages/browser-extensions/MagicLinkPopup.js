import { Fragment, useEffect } from 'react'
import { Transition } from '@headlessui/react';
import { CheckCircleIcon, ClipboardCheckIcon, ClipboardIcon } from '@heroicons/react/outline'
import { XIcon } from '@heroicons/react/solid'
import { useSelector, useDispatch } from 'react-redux';
import { __ } from '@wordpress/i18n';
import {CopyToClipboard} from 'react-copy-to-clipboard';

export default function MagicLinkPopup() {

	const dispatch = useDispatch();

	const magicLinkPopup = useSelector( ( state ) => state.magicLinkPopup );

	const copyText = magicLinkPopup.link;
	const [copied, setCopied] = React.useState(false);
	const onCopy = React.useCallback(() => {
		setCopied(true);
		setTimeout(() => {
			setCopied(false);
		}, 2000);
	}, [])

	useEffect( () => {
		if ( '' !== magicLinkPopup ) {
			setTimeout( ()=>{
			dispatch( {type: 'GENERATE_MAGIC_LINK_POPUP', payload: '' } );
			}, 120000 );
		}
	}, [magicLinkPopup] );

	return (
		<>
			{ /* Global notification live region, render this permanently at the end of the document */ }
			<div aria-live="assertive" className="fixed inset-10 z-10 flex px-4 py-6 pointer-events-none sm:p-6 sm:items-start">
				<div className="w-full h-full flex flex-col items-center space-y-4 justify-center">
					{/* Notification panel, dynamically insert this into the live region when it needs to be displayed */}
					<Transition
						show={ '' === magicLinkPopup ? false : true }
						as={Fragment}
						enter="transform ease-out duration-300 transition"
						enterFrom="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
						enterTo="translate-y-0 opacity-100 sm:translate-x-0"
						leave="transition ease-in duration-100"
						leaveFrom="opacity-100"
						leaveTo="opacity-0"
					>
						<div className="w-full h-full z-1 before:absolute before:left-0 before:top-0 before:content-[''] before:w-full before:h-full">
							<div className="p-4 max-w-sm w-full bg-white rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden z-[2] absolute left-[50%] top-[50%] translate-x-[-50%] translate-y-[-50%] shadow-[0_0_100px_200px_rgba(0,0,0,0.20)]">
								<div className="flex items-start">
									<div className="flex-shrink-0">
										<CheckCircleIcon className="h-6 w-6 text-green-400" aria-hidden="true" />
									</div>
									<div className="ml-3 w-0 flex-1 pt-0.5">
										<p className="text-[16px] font-medium text-gray-900">{ magicLinkPopup.message }</p>
										<div className='mt-5 relative'>
											<CopyToClipboard onCopy={onCopy} text={copyText}>
												<code className='text-[16px] font-medium break-all'>{ magicLinkPopup.link }</code>										
											</CopyToClipboard>
											
											<div className='absolute top-0 right-[-13%]'>
												{copied ? 
													<ClipboardCheckIcon className="h-5 w-5 text-[#50d71e]" aria-hidden="true" /> :
													<CopyToClipboard onCopy={onCopy} text={copyText}> 
														<ClipboardIcon className="h-5 w-5" aria-hidden="true" />
													</CopyToClipboard>
													}
											</div>				
										</div>
									</div>
									<div className="ml-4 flex-shrink-0 flex">
										<button
											className="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
											onClick={() => {
												dispatch( {type: 'GENERATE_MAGIC_LINK_POPUP', payload: '' } );
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
