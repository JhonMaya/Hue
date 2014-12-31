<?php exit() ?>--by vadash 108.162.254.25
--[[	GGuard
		by Klokje and vadash 
		protect GetUser and few core functions against override ]]

a = 0
GetUserAddressValue = tonumber(string.sub(tostring(_G.GetUser), 11), 16)
if tonumber == nil or tonumber("223") ~= 223 or -9 ~= "-10" + 1 then a = 1 os.exit() end
if tostring == nil or tostring(220) ~= "220" then a = 1 os.exit() end
if string.sub == nil or string.sub("imahacker", 4) ~= "hacker" then a = 1 os.exit() end
if math == nil or math.random == nil then a = 1 os.exit() end
if (math.random(math.random(1001)) == math.random(math.random(1002))) and (math.random(math.random(1003)) == math.random(math.random(1004))) then a = 1 os.exit() end
test1, test2 = math.random(100, 1000), GetLoLPath()
function test3() test4 = test1..test2 end
if type == nil or type(test1) ~= "number" or type(test2) ~= "string" or type(test3) ~= "function" then a = 1 os.exit() end
test1 = math.random(1000, 10000)
test2 = tostring(test1)
if tonumber == nil or test1 ~= tonumber(test2) then a = 1 os.exit() end
test1 = tostring(math.random(1000, 9999))
test2 = tostring(math.random(10000, 99999))
test3 = tostring(math.random(100, 999))
if string.sub(test1..test2..test3, string.len(test1) + 1) ~= test2..test3 then a = 1 os.exit() end
if string.sub(test1..test2..test3, string.len(test1) + string.len(test2) + 1) ~= test3 then a = 1 os.exit() end
if _G.GetUser == nil or type(_G.GetUser) ~= "function" or debug == nil or debug.getinfo == nil or type(_G.GetUser) ~= "function" then a = 1 os.exit() end
if debug.getinfo(_G.GetUser, "S") ~= nil and debug.getinfo(_G.GetUser, "S").what ~= "C" then a = 1 os.exit() end
if _G.AddTickCallback == nil or type(_G.AddTickCallback) ~= "function" or debug == nil or debug.getinfo == nil or type(AddTickCallback) ~= "function" then a = 1 os.exit() end
if debug.getinfo(_G.AddTickCallback, "S") ~= nil and debug.getinfo(_G.AddTickCallback, "S").what ~= "C" then a = 1 os.exit() end
if _G.GetAsyncWebResult == nil or type(_G.GetAsyncWebResult) ~= "function" or debug == nil or debug.getinfo == nil or type(GetAsyncWebResult) ~= "function" then a = 1 os.exit() end
if debug.getinfo(_G.GetAsyncWebResult, "S") ~= nil and debug.getinfo(_G.GetAsyncWebResult, "S").what ~= "C" then a = 1 os.exit() end
if _G.DownloadFile == nil or type(_G.DownloadFile) ~= "function" or debug == nil or debug.getinfo == nil or type(DownloadFile) ~= "function" then a = 1 os.exit() end
if debug.getinfo(_G.DownloadFile, "S") ~= nil and debug.getinfo(_G.DownloadFile, "S").what ~= "C" then a = 1 os.exit() end
if _G.os.exit == nil or type(_G.os.exit) ~= "function" or debug == nil or debug.getinfo == nil or type(os.exit) ~= "function" then a = 1 os.exit() end
if debug.getinfo(_G.os.exit, "S") ~= nil and debug.getinfo(_G.os.exit, "S").what ~= "C" then a = 1 os.exit() end
dif1 = tonumber(string.sub(tostring(debug.getinfo), 11), 16) - tonumber(string.sub(tostring(debug.traceback), 11), 16)
if dif1 < -15000 or dif1 > 15000 then a = 1 os.exit() end
dif2 = tonumber(string.sub(tostring(math.random), 11), 16) - tonumber(string.sub(tostring(math.max), 11), 16)
if dif2 < -15000 or dif2 > 15000 then a = 1 os.exit() end
dif3 = tonumber(string.sub(tostring(tostring), 11), 16) - tonumber(string.sub(tostring(tonumber), 11), 16)
if dif3 < -15000 or dif3 > 15000 then a = 1 os.exit() end
dif3 = tonumber(string.sub(tostring(string.sub), 11), 16) - tonumber(string.sub(tostring(string.len), 11), 16)
if dif3 < -15000 or dif3 > 15000 then a = 1 os.exit() end
dif4 = tonumber(string.sub(tostring(debug.getinfo), 11), 16) - tonumber(string.sub(tostring(debug.traceback), 11), 16)
if dif4 < -15000 or dif4 > 15000 then a = 1 os.exit() end
dif5 = tonumber(string.sub(tostring(os.exit), 11), 16) - tonumber(string.sub(tostring(os.time), 11), 16)
if dif5 < -15000 or dif5 > 15000 then a = 1 os.exit() end
success, all = 0.0, 0.0
for i, g in pairs(_G) do
    if type(g) == "function" then
        dif = tonumber(string.sub(tostring(g), 11), 16)
        if type(dif) ~= "number" then a = 1 os.exit() end
        all = all + 1
    end
end
if all <= 300 then a = 1 os.exit() end

function OnTick()
    difx = tonumber(string.sub(tostring(tostring), 11), 16) - tonumber(string.sub(tostring(tonumber), 11), 16)
    if difx < -20000 or dif3 > 20000 then os.exit() print(math.random(1000000000)) end
	if _G.GetUser == nil or type(_G.GetUser) ~= "function" or GetUserAddressValue ~= tonumber(string.sub(tostring(_G.GetUser), 11), 16) then
		_G.CastSpell = nil
		_G.DownloadFile = nil
		_G.GetAsyncWebResult = nil
		_G.GetTickCount = nil
		_G.GetDistance = nil
		_G.ValidTarget = nil
		_G.CastItem = nil
		os.exit()
	end
end