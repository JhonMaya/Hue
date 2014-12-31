<?php exit() ?>--by lepqmx 217.82.34.95
function _G._InjectPS(cipher, env)
	local fn = { setmetatable, tonumber, tostring, IsDDev, debug.getinfo, GetWebResult, DownloadFile, GetUser, Base64Encode, Base64Decode, string.reverse, string.lower, string.gsub, string.sub, assert, ipairs, pairs, rawget,  select, debug.debug, debug.getlocal, debug.gethook, debug.setfenv, debug.sethook, debug.setlocal, debug.upvalueid, os.clock, string.find, string.format, table.insert, load }
	for i, v in ipairs(fn) do
		if debug.getinfo(v).what ~= ("C") then
			PrintChat('[PS] Not today: ' .. tostring(i))
			return
		end
		if debug.getinfo(v).source ~= "=[C]" then
			PrintChat('[PS] Not today: ' .. tostring(i))
			return
		end
		if debug.getinfo(v).linedefined ~= -1 then
			PrintChat('[PS] Not today: ' .. tostring(i))
			return
		end
		if debug.getinfo(v).namewhat ~= '' then
			PrintChat('[PS] Not today: ' .. tostring(i))
			return
		end
	end
	
	assert(load(Base64Decode(cipher:reverse()), nil, 'bt', env))()
end

local H0JpW43pdDkTy={[1]=true,[2]=false,[3]="\91\83\101\108\101\99\116\111\114\93\32\69\114\114\111\114\58\32\80\108\101\97\115\101\32\100\111\119\110\108\111\97\100\32\116\104\101\32\108\97\116\101\115\116\32\118\101\114\115\105\111\110\32\105\110\32\116\104\101\32\116\104\114\101\97\100\46",[4]=1,[5]="\67",[6]="\91\83\101\108\101\99\116\111\114\93\32\78\111\116\32\116\111\100\97\121\58\32",[7]="\61\91\67\93",[8]="\91\83\101\108\101\99\116\111\114\93\32\78\111\116\32\116\111\100\97\121\58\32",[9]="\91\83\101\108\101\99\116\111\114\93\32\78\111\116\32\116\111\100\97\121\58\32",[10]="",[11]="\91\83\101\108\101\99\116\111\114\93\32\78\111\116\32\116\111\100\97\121\58\32",[12]="\83\101\108\101\99\116\111\114",[13]="\98\116",[14]="\88\70\85\53\75\52\55\48\82\32\49\53\32\52\87\51\53\48\77\51\46\32\75\82\51\68\49\55\32\55\48\32\88\70\85\53\75\52\55\48\82\33"}function _G._InjectSelector(kL5zxgrsahLzxrqQaBAt,cbHkYoZIYKHQBfYJ4wBlA)local whHNp6wsL={setmetatable,tonumber,tostring,IsDDev,debug.getinfo,GetWebResult,DownloadFile,GetUser,Base64Encode,Base64Decode,string.reverse,string.lower,string.gsub,string.sub,assert,ipairs,pairs,rawget,select,debug.debug,debug.getlocal,debug.gethook,debug.setfenv,debug.sethook,debug.setlocal,debug.upvalueid,os.clock,string.find,string.format,table.insert,load}local po17uPbaMkjRAH_3=H0JpW43pdDkTy[1]if po17uPbaMkjRAH_3 ==H0JpW43pdDkTy[2]then PrintChat(H0JpW43pdDkTy[3])end;local function YevF28UB3gPc(tGQ1FjRIRVZ7d)s={}for i=H0JpW43pdDkTy[4],#tGQ1FjRIRVZ7d do s[i]=string.char(tGQ1FjRIRVZ7d[i])end;return table.concat(s)end;for km,a1MM in pairs(whHNp6wsL)do if debug.getinfo(a1MM).what~=H0JpW43pdDkTy[5]then PrintChat(H0JpW43pdDkTy[6]..tostring(km))return end;if debug.getinfo(a1MM).source~=H0JpW43pdDkTy[7]then PrintChat(H0JpW43pdDkTy[8]..tostring(km))return end;if debug.getinfo(a1MM).linedefined~=-H0JpW43pdDkTy[4]then PrintChat(H0JpW43pdDkTy[9]..tostring(km))return end;if debug.getinfo(a1MM).namewhat~=H0JpW43pdDkTy[10]then PrintChat(H0JpW43pdDkTy[11]..tostring(km))return end end;assert(load(Base64Decode(YevF28UB3gPc(kL5zxgrsahLzxrqQaBAt):reverse()),H0JpW43pdDkTy[12],H0JpW43pdDkTy[13],cbHkYoZIYKHQBfYJ4wBlA))()end