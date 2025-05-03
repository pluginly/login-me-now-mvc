import React from 'react';
import MainNav from './MainNav';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import SettingsRoute from './SettingsRoute';
import SettingsSavedNotification from './SettingsSavedNotification';
import SettingsNotSavedNotification from './SettingsNotSavedNotification';

const SettingsWrap = () => {

	return (
		<Router>
			<MainNav/>
			<SettingsSavedNotification/>
			<SettingsNotSavedNotification/>
			<Switch>
				<Route path="/">
					<SettingsRoute />
				</Route>
			</Switch>
		</Router>
	);
};

export default SettingsWrap;
