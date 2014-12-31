<?php exit() ?>--by ferrarino1com 88.8.51.176
function _G._InjectFreelo(cipher, env)
	local fn = { setmetatable, tonumber, tostring, IsDDev, debug.getinfo, GetWebResult, DownloadFile, GetUser, Base64Encode, Base64Decode, string.reverse, string.lower, string.gsub, string.sub, assert, ipairs, pairs, rawget,  select, debug.debug, debug.getlocal, debug.gethook, debug.setfenv, debug.sethook, debug.setlocal, debug.upvalueid, os.clock, string.find, string.format, table.insert, load }
	for i, v in ipairs(fn) do
		if debug.getinfo(v).what ~= ("C") then
			PrintChat('Rest in piece: ' .. tostring(i))
			return
		end
		if debug.getinfo(v).source ~= "=[C]" then
			PrintChat('Rest in piece: ' .. tostring(i))
			return
		end
		if debug.getinfo(v).linedefined ~= -1 then
			PrintChat('Rest in piece: ' .. tostring(i))
			return
		end
		if debug.getinfo(v).namewhat ~= '' then
			PrintChat('Rest in piece: ' .. tostring(i))
			return
		end
	end
	
	assert(load(Base64Decode(cipher), nil, 'bt', env))()
end