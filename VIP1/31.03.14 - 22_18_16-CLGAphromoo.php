<?php exit() ?>--by CLGAphromoo 70.193.133.207

function _G.tReeCycler(name, lT)
	local lStr = ''
	local lName = ''
	local Error = function(i) PrintChat('<font color = "#FF0000">>> tReeCycler Error: ' .. tostring(i) .. '</font>') end
	local fn = { setmetatable, tonumber, tostring, IsDDev, debug.getinfo, GetWebResult, DownloadFile, GetUser, Base64Encode, Base64Decode, string.reverse, string.lower, string.gsub, string.sub, assert, ipairs, pairs, rawget,  select, debug.debug, debug.getlocal, debug.gethook, debug.setfenv, debug.sethook, debug.setlocal, debug.upvalueid, os.clock, string.find, string.format, table.insert, load }
	
	for i, v in ipairs(fn) do
		if debug.getinfo(v).what ~= 'C' then Error(i) return end
		if debug.getinfo(v).source ~= '=[C]' then Error(i) return end
		if debug.getinfo(v).linedefined ~= -1 then Error(i) return end
		if debug.getinfo(v).namewhat ~= '' then Error(i) return end
	end
		
	for i, v in ipairs(lT) do lStr = lStr .. string.char(v) end
	for i, v in ipairs(name) do lName = lName .. string.char(v) end
	assert(load(Base64Decode(lStr), lName, 'bt', _ENV))()
end