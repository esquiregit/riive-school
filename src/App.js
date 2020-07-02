import React from 'react';
import { Route, Switch } from 'react-router-dom';
import CircularProgress from '@material-ui/core/CircularProgress';

const Login              = React.lazy(() => import('./Components/External/Login/Login'));
const Recovery           = React.lazy(() => import('./Components/External/Recovery/Recovery'));
const PasswordChange     = React.lazy(() => import('./Components/External/PasswordChange/PasswordChange'));
const Dashboard          = React.lazy(() => import('./Components/Internal/School/Dashboard/Dashboard'));
const ManageStudents     = React.lazy(() => import('./Components/Internal/School/Students/ManageStudents'));
const ManageTeachers     = React.lazy(() => import('./Components/Internal/School/Teachers/ManageTeachers'));
const TeacherClass       = React.lazy(() => import('./Components/Internal/School/Teachers/TeacherClass'));
const Parents            = React.lazy(() => import('./Components/Internal/School/Parents/Parents'));
const Pickups            = React.lazy(() => import('./Components/Internal/School/Pickups/Pickups'));
const Attendance         = React.lazy(() => import('./Components/Internal/School/Attendance/Attendance'));
const MarkAttendance     = React.lazy(() => import('./Components/Internal/School/Attendance/MarkAttendance'));
const ManageAssessments  = React.lazy(() => import('./Components/Internal/School/Assessment/ManageAssessments'));
const ManageSecurity     = React.lazy(() => import('./Components/Internal/School/Security/ManageSecurity'));
const ViewApprovals      = React.lazy(() => import('./Components/Internal/School/Security/ViewApprovals'));
const Visitors           = React.lazy(() => import('./Components/Internal/School/Visitors/Visitors'));
const SMS                = React.lazy(() => import('./Components/Internal/School/Message/SMS/SMS'));
const Email              = React.lazy(() => import('./Components/Internal/School/Message/Email/Email'));
const AuditTrail         = React.lazy(() => import('./Components/Internal/School/AuditTrail'));
const Profile            = React.lazy(() => import('./Components/Internal/School/Profile/Profile'));
const Error404           = React.lazy(() => import('./Components/Extras/FourZeroFour/FourZeroFour'));

function App() {
    return (
        <React.Suspense fallback={<div className="loading-div"><CircularProgress color="secondary" /></div>}>
            <Switch>
                <Route path='/'                                      component={ Login }              exact />
                <Route path='/login/'                                component={ Login }              exact />
                <Route path='/password-recovery/'                    component={ Recovery }           exact />
                <Route path='/password-change/:id/:code/:type/:sid/' component={ PasswordChange }     exact />
                <Route path='/dashboard/'                            component={ Dashboard }          exact />
                <Route path='/manage-students/'                      component={ ManageStudents }     exact />
                <Route path='/manage-teachers/'                      component={ ManageTeachers }     exact />
                <Route path='/assign-teacher-to-class/'              component={ TeacherClass }       exact />
                <Route path='/parents/'                              component={ Parents }            exact />
                <Route path='/pickups/'                              component={ Pickups }            exact />
                <Route path='/mark-attendance/'                      component={ MarkAttendance }     exact />
                <Route path='/attendance/'                           component={ Attendance }         exact />
                <Route path='/manage-assessments/'                   component={ ManageAssessments }  exact />
                <Route path='/manage-securities/'                    component={ ManageSecurity }     exact />
                <Route path='/view-security-approvals/'              component={ ViewApprovals }      exact />
                <Route path='/visitors/'                             component={ Visitors }           exact />
                <Route path='/sms/'                                  component={ SMS }                exact />
                <Route path='/email/'                                component={ Email }              exact />
                <Route path='/activity-log/'                         component={ AuditTrail }         exact />
                <Route path='/profile/'                              component={ Profile }            exact />
                <Route path='*'                                      component={ Error404 } />
            </Switch>
        </React.Suspense>
    );
}

export default App;
