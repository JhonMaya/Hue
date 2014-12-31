<?php exit() ?>--by x7x 89.70.161.4
if myHero.charName ~= "Nidalee" then return end
local scriptReady = false
local varFirstLoad = true
local varUpdated = false -- temp fix
local versionGOE = 0.91
local nameGOE = "Nidalee - Flying Spears"
local fnameGOE = "aNidalee"
local needUpdate_GOE = false
local needRun_GOE = true
local needRun_GOE_2 = true
local URL_GOE = "http://anony4bol.comoj.com/Nidalee.lua"
local PATH_GOE = BOL_PATH.."Scripts\\"..fnameGOE..".lua"
local DownloadedTable = false
local DownloadingTable = false
local b='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
function DecodeB64(data)
    data = string.gsub(data, '[^'..b..'=]', '')
    return (data:gsub('.', function(x)
        if (x == '=') then return '' end
        local r,f='',(b:find(x)-1)
        for i=6,1,-1 do r=r..(f%2^i-f%2^(i-1)>0 and '1' or '0') end
        return r;
    end):gsub('%d%d%d?%d?%d?%d?%d?%d?', function(x)
        if (#x ~= 8) then return '' end
        local c=0
        for i=1,8 do c=c+(x:sub(i,i)=='1' and 2^(8-i) or 0) end
        return string.char(c)
    end))
end
function SplitString(s, delimiter)
    result = {};
    for match in (s..delimiter):gmatch("(.-)"..delimiter) do
        table.insert(result, match);
    end
    return result;
end
local tabZ = {}
function decGOE(data)
	if data ~= nil then
		local nString = DecodeB64(tostring(data))
		tabZ = SplitString(nString, "||")
		DownloadedTable = true
	end
end
function CheckVersionGOE(data)
	local onlineVerGOE = tonumber(data)
	if type(onlineVerGOE) ~= "number" then return end
	if onlineVerGOE and onlineVerGOE > versionGOE then
		print("<font color='#00BFFF'>AUTOUPDATER: There is a new version of "..nameGOE..". Don't F9 till done...</font>") 
		print("<font color='#00BFFF'>AUTOUPDATER: Current: v"..versionGOE..", New: "..onlineVerGOE.."</font>") 
		needUpdate_GOE = true 
	elseif onlineVerGOE and onlineVerGOE <= versionGOE then
		varUpdated = true
	end
end
function UpdateScriptGOE()
	
	if needRun_GOE then
		needRun_GOE = false
		if debug.getinfo(GetAsyncWebResult).what == "C" then GetAsyncWebResult("anony4bol.comoj.com", "NidaleeVer.lua", CheckVersionGOE) end
	end
	if needRun_GOE_2 and not needRun_GOE then
		needRun_GOE_2 = false
		if debug.getinfo(GetAsyncWebResult).what == "C" then GetAsyncWebResult("anony4bol.comoj.com", "vipusers.php", decGOE) end
	end

	if needUpdate_GOE then
		needUpdate_GOE = false
		DownloadFile(URL_GOE, PATH_GOE, function()
                if FileExist(PATH_GOE) then
                    print("<font color='#00BFFF'>AUTOUPDATER: Script updated! Reload scripts to use new version!</font>")
                end
            end)
	end
end
AddTickCallback(UpdateScriptGOE)

local QCollision = nil
local Prodict = ProdictManager.GetInstance()
local QProdict = nil
local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,1500,DAMAGE_MAGICAL,false)
local Enemies = {}
local CanQ = false
local waittxt = {}
function OnUnload()
	if scriptReady then
		PrintChat("<font color='#00BFFF'>"..nameGOE.." v"..versionGOE.." unloaded!</font>")
	end
end
function OnTick()
	if varFirstLoad == true and varUpdated == true then
		local v1 = debug.getinfo(GetUser)
		local v2 = v1.what
		if v2 == "C" then
			local v3 = debug.getinfo(debug.getinfo)
			local v4 = v3.what
			if v4 == "C" then
				if DownloadedTable then
					for i, x in pairs(tabZ) do
						--PrintChat(tostring(x))
						if tostring(x) == GetUser() then
							QProdict = Prodict:AddProdictionObject(_Q, 1700, 1300, 0.125, 70, myHero)
							QCollision = Collision(1700, 1300, 0.125, 70)
							for i = 1, heroManager.iCount do
								local hero = heroManager:GetHero(i)
								if hero.team ~= myHero.team then
									QProdict:GetPredictionOnDash(hero, CastQcallback)
									QProdict:GetPredictionAfterDash(hero, CastQcallback)
									QProdict:GetPredictionOnImmobile(hero, CastQcallback)
									QProdict:GetPredictionAfterImmobile(hero, CastQcallback)
								end
							end
							Menu = scriptConfig(nameGOE.." v"..versionGOE, "aniddaleesonzos")
							ts.name = "Nidalee TS"
							Menu:addTS(ts)
							Menu:addSubMenu("General information", "InfoMenu")
							Menu.InfoMenu:addParam("Information1", "    "..nameGOE.." v"..versionGOE, SCRIPT_PARAM_INFO, "")
							Menu.InfoMenu:addParam("Information2", "Huge thanks for donation and support!", SCRIPT_PARAM_INFO, "")
							Menu.InfoMenu:addParam("Information3", "Enjoy script and report bugs on forums!", SCRIPT_PARAM_INFO, "")
							Menu:addSubMenu("Main settings", "MainMenu")
							Menu.MainMenu:addParam("useQ","Override Q casting with prodiction", SCRIPT_PARAM_ONOFF, true)
							Menu.MainMenu:addParam("useQcol","- use collision?", SCRIPT_PARAM_ONOFF, true)
							Menu.MainMenu:addParam("callbacks","Cast Q with prodiction callbacks", SCRIPT_PARAM_ONOFF, true)
							Menu.MainMenu:addParam("callbackscol","- use collision?", SCRIPT_PARAM_ONOFF, true)
							Menu:addSubMenu("Drawer settings", "DrawHelperMenu")
							Menu.DrawHelperMenu:addParam("drawQrange","Draw Q range", SCRIPT_PARAM_ONOFF, true)
							Menu.DrawHelperMenu:addParam("drawHPtext","Draw damage output info", SCRIPT_PARAM_ONOFF, true)
							local ordertxt = 1
							for i=1, heroManager.iCount do
								waittxt[i] = ordertxt
								ordertxt = ordertxt+1
								local EnemyHero = heroManager:getHero(i)
								if EnemyHero ~= nil and EnemyHero.team == TEAM_ENEMY then table.insert(Enemies, EnemyHero) end
							end
							PrintChat("<font color='#00BFFF'>"..nameGOE.." v"..versionGOE.." loaded!</font>")
							scriptReady = true
							varFirstLoad = false
						end
					end
				end
			end
		end
	elseif scriptReady and not varFirstLoad and varUpdated then
		ts:update()
		CanQ = (myHero:CanUseSpell(_Q) == READY)
	end
end
local IsQProdicted = false
function OnSendPacket(p)
	local PacketD = Packet(p)
	if p.header == PacketD.headers.S_CAST and PacketD:get('spellId') == SPELL_1 and IsHumanForm() then
		if not IsQProdicted then
			if ts.target ~= nil then
				p:Block()
				CastQ(ts.target)
			end
		else
			IsQProdicted = false
		end
	end
end
function IsHumanForm()
	--PrintChat(myHero:GetSpellData(_Q).name)
	if myHero:GetSpellData(_Q).name == "JavelinToss" then
		return true
	else
		return false
	end
end
function CastQ(Target)
	if Menu.MainMenu.useQ and Target ~= nil and not Target.dead then
		local pos = QProdict:GetPrediction(Target, myHero)
		if pos ~= nil and GetDistance(pos) < 1300 then
			IsQProdicted = true
			if Menu.MainMenu.useQcol and not QCollision:GetMinionCollision(myHero, pos) then
				CastSpell(_Q, pos.x, pos.z)
			else
				CastSpell(_Q, pos.x, pos.z)
			end
		end
	end
end
function CastQcallback(unit, pos, spell)
    if GetDistance(pos) < 1300 and CanQ then
		IsQProdicted = true
		if Menu.MainMenu.useQcol and not QCollision:GetMinionCollision(myHero, pos) then
			CastSpell(_Q, pos.x, pos.z)
		else
			CastSpell(_Q, pos.x, pos.z)
		end
    end
end
function round(num, idp)
  return tonumber(string.format("%." .. (idp or 0) .. "f", num))
end
function OnDraw()
	if scriptReady then
		if Menu.DrawHelperMenu.drawQrange and CanQ then DrawCircle(myHero.x, myHero.y, myHero.z, 1500, RGB(0,191,255)) end
		if Menu.DrawHelperMenu.drawHPtext then
			for n, DrawTarget in pairs(Enemies) do
				if DrawTarget ~= nil and DrawTarget.valid and not DrawTarget.dead and DrawTarget.team == TEAM_ENEMY then
					local Qdmg = 0
					Qdmg = getDmg("Q", DrawTarget, myHero)
					if CanQ then
						if waittxt[n] == 1 then 
							if DrawTarget.health < Qdmg then
								PrintFloatText(DrawTarget, 0, "HP: DEAD")
								waittxt[n] = 10
							else
								local s = tostring(round(DrawTarget.health-Qdmg))
								PrintFloatText(DrawTarget, 0, "HP: "..s)
								waittxt[n] = 10
							end
						else
							waittxt[n] = waittxt[n]-1
						end
					else
						if waittxt[n] == 1 then 
							PrintFloatText(DrawTarget, 0, "Not killable")
							waittxt[n] = 10
						else
							waittxt[n] = waittxt[n]-1
						end
					end
				end
			end	
		end
	end
end