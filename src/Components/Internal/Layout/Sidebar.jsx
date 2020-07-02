import React from 'react';
import SidebarSchool from "./SidebarSchool"
import SidebarTeacher from "./SidebarTeacher"

export const getSidebar = access_level => {
    if(access_level.toLowerCase() === 'school') {
        return <SidebarSchool />
    } else if(access_level.toLowerCase() === 'teacher') {
        return <SidebarTeacher />
    }
}