<?php exit() ?>--by vadash 108.162.254.29
GetUserAddressValue = tonumber(string.sub(tostring(_G.GetUser), 11), 16)
-- simple check
if tonumber == nil or tonumber("223") ~= 223 or -9 ~= "-10" + 1 then return end
if tostring == nil or tostring(220) ~= "220" then return end
if string.sub == nil or string.sub("imahacker", 4) ~= "hacker" then return end
local function protectra(table) return setmetatable({}, { __index = table, __newindex = function(table, key, value) end, __metatable = false }) end
-- math random check
if math == nil or math.random == nil then return end
if (math.random(math.random(1001)) == math.random(math.random(1002))) and (math.random(math.random(1003)) == math.random(math.random(1004))) then return end
-- TYPE test
test1, test2 = math.random(100, 1000), GetLoLPath()
function test3() test4 = test1..test2 end
if type == nil or type(test1) ~= "number" or type(test2) ~= "string" or type(test3) ~= "function" then return end
-- TONUMBER TOSTRING test
test1 = math.random(1000, 10000)
test2 = tostring(test1)
if tonumber == nil or test1 ~= tonumber(test2) then return end
-- STRING.SUB test
test1 = tostring(math.random(1000, 9999))
test2 = tostring(math.random(10000, 99999))
test3 = tostring(math.random(100, 999))
if string.sub(test1..test2..test3, string.len(test1) + 1) ~= test2..test3 then return end
if string.sub(test1..test2..test3, string.len(test1) + string.len(test2) + 1) ~= test3 then return end
-- chain check
local olddtostring = tostring
function debuggetinfo2() print(1) end
if debug.getinfo(tostring, "S").what ~= "C" then return end
_G.tostring = debuggetinfo2
if debug.getinfo(tostring, "S").what ~= "Lua" then return end
_G.tostring = olddtostring
if debug.getinfo(tostring, "S").what ~= "C" then return end
-- C func number check+
nC = 0
for i, g in pairs(_G) do
    if math.random(1, 10) == 3 then
        if debug.getinfo(GetTickCount, "S").what ~= "C" then return end
    end
    if math.random(1, 10) == 5 then
        if debug.getinfo(CastItem, "S").what ~= "Lua" then return end
    end 
    if type(g) == "function" then
        if debug.getinfo(g, "f").func ~= g then return end
        if debug.getinfo(g, "S").what == "C" then nC = nC +1 end
    end
end
if nC < 148-12 or nC > 148+12 then return end --print(nC)
-- _G.GetUser check
if _G.GetUser == nil or type(_G.GetUser) ~= "function" or debug == nil or debug.getinfo == nil or type(_G.GetUser) ~= "function" then return end
if debug.getinfo(_G.GetUser, "S") ~= nil and debug.getinfo(_G.GetUser, "S").what ~= "C" then return end
-- few more funcs checks
if debug.getinfo(_G.AddTickCallback, "S").what ~= "C" then return end
if debug.getinfo(_G.GetAsyncWebResult, "S").what ~= "C" then return end
if debug.getinfo(_G.DownloadFile, "S").what ~= "C" then return end
if debug.getinfo(_G.os.exit, "S").what ~= "C" then return end
if debug.getinfo(_G.io.open, "S").what ~= "C" then return end
if debug.getinfo(_G.load, "S").what ~= "C" then return end
if debug.getinfo(_G.Base64Decode, "S").what ~= "C" then return end
-- Core func checks
dif1 = tonumber(string.sub(tostring(debug.getinfo), 11), 16) - tonumber(string.sub(tostring(debug.traceback), 11), 16)
if dif1 < -15000 or dif1 > 15000 then return end
dif2 = tonumber(string.sub(tostring(math.random), 11), 16) - tonumber(string.sub(tostring(math.max), 11), 16)
if dif2 < -15000 or dif2 > 15000 then return end
dif3 = tonumber(string.sub(tostring(tostring), 11), 16) - tonumber(string.sub(tostring(tonumber), 11), 16)
if dif3 < -15000 or dif3 > 15000 then return end
dif3 = tonumber(string.sub(tostring(string.sub), 11), 16) - tonumber(string.sub(tostring(string.len), 11), 16)
if dif3 < -15000 or dif3 > 15000 then return end
dif4 = tonumber(string.sub(tostring(debug.getinfo), 11), 16) - tonumber(string.sub(tostring(debug.traceback), 11), 16)
if dif4 < -15000 or dif4 > 15000 then return end
dif5 = tonumber(string.sub(tostring(os.exit), 11), 16) - tonumber(string.sub(tostring(os.time), 11), 16)
if dif5 < -15000 or dif5 > 15000 then return end
dif6 = tonumber(string.sub(tostring(io.open), 11), 16) - tonumber(string.sub(tostring(os.getenv), 11), 16)
if dif6 < -15000 or dif6 > 15000 then return end
-- protection against function name checks
success, all = 0.0, 0.0
for i, g in pairs(_G) do
    if type(g) == "function" then
        dif = tonumber(string.sub(tostring(g), 11), 16)
        if type(dif) ~= "number" then return end
        all = all + 1
    end
end
if all <= 300 then os.exit() end