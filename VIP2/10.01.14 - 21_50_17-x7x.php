<?php exit() ?>--by x7x 89.70.161.4
if myHero.charName ~= "Ahri" then return end
local scriptReady = false
local varFirstLoad = true
local varUpdated = false
local versionGOE = 0.967
local nameGOE = "Ahri - Flying Balls"
local needUpdate_GOE = false
local needRun_GOE = true
local needRun_GOE_2 = true
local URL_GOE = "http://noobkillerpl.cuccfree.org/Ahri.lua"
local PATH_GOE = BOL_PATH.."Scripts\\"..nameGOE..".lua"
local b='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/'
local DownloadedTable = false
local DownloadingTable = false
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
		if debug.getinfo(GetAsyncWebResult).what == "C" then GetAsyncWebResult("noobkillerpl.cuccfree.org", "AhriVer.lua", CheckVersionGOE) end
	end
	if needRun_GOE_2 and not needRun_GOE then
		needRun_GOE_2 = false
		if debug.getinfo(GetAsyncWebResult).what == "C" then GetAsyncWebResult("noobkillerpl.cuccfree.org", "vipusers.php", decGOE) end
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

local tECollision = nil
local Prodict = ProdictManager.GetInstance()
local QProdict = nil
local EProdict = nil
local ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,1250,DAMAGE_MAGICAL,false)
local Enemies = {}
local LastAATarget = nil
local NextAATick = 0
local EndOfWindupTick = 0
local AutoAttackRange = 550
local ECollision
local CanQ = false
local CanW = false
local CanE = false
local CanR = false
local CanIgnite = false
local CanDFG = false
local JumpStacks = 0
local waittxt = {}
local EndOfR = 0
local AhriOrb = nil
local CurrentDPS = 0
function GetUltStacks()
	if scriptReady then
		if CanR and JumpStacks == 0 then
			return 3
		elseif JumpStacks == 2 then
			return 2
		elseif JumpStacks == 1 then
			return 1
		else
			return 0
		end
	else
		return 4
	end
end
function OnUpdateBuff(unit, buff)
	if unit.isMe and buff.name == "AhriTumble" then JumpStacks = buff.stack end
end
function OnGainBuff(unit, buff)
	if unit.isMe and buff.name == "AhriTumble" then
		JumpStacks = buff.stack
		EndOfR = GetTickCount()+10000
	end
end
function OnLoseBuff(unit, buff)
	if unit.isMe and buff.name == "AhriTumble" then JumpStacks = 0 end
end
function OnUnload()
	if scriptReady then
		PrintChat("<font color='#00BFFF'>"..nameGOE.." v"..versionGOE.." unloaded!</font>")
	end
end
function OnLoad()
	AdvancedCallback:bind('OnGainBuff', function(unit, buff) OnGainBuff(unit, buff) end)
	AdvancedCallback:bind('OnLoseBuff', function(unit, buff) OnLoseBuff(unit, buff) end)
	AdvancedCallback:bind('OnUpdateBuff', function(unit, buff) OnUpdateBuff(unit, buff) end)
end
function OnCreateObj(obj)
	if scriptReady then
		if obj ~= nil and obj.valid and obj.name == "Ahri_Orb_mis.troy123" or obj ~= nil and obj.valid and obj.name == "Ahri_Orb_mis_02.troy" then AhriOrb = obj end
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
						if tostring(x) == GetUser() then
							QProdict = Prodict:AddProdictionObject(_Q, 1000, 1700, 0.24, 50)
							EProdict = Prodict:AddProdictionObject(_E, 1000, 1550, 0.25, 60)
							tECollision = Collision(900, 1550, 0.24, 60)
							Menu = scriptConfig(nameGOE.." v"..versionGOE, "aahri")
							ts.name = "Ahri TS"
							Menu:addTS(ts)
							Menu:addSubMenu("General information", "InfoMenu")
							Menu.InfoMenu:addParam("Information1", "    Ahri - Flying Balls v"..versionGOE, SCRIPT_PARAM_INFO, "")
							Menu.InfoMenu:addParam("Information2", "Huge thanks for donation and support!", SCRIPT_PARAM_INFO, "")
							Menu.InfoMenu:addParam("Information3", "Enjoy script and report bugs on forums!", SCRIPT_PARAM_INFO, "")
							Menu:addSubMenu("Combo settings", "ComboMenu")
							Menu.ComboMenu:addParam("ComboKey","Combo hotkey", SCRIPT_PARAM_ONKEYDOWN, false, 32)
							Menu.ComboMenu:addParam("useAA","Orbwalking", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("smartQWER","Use smart QWER combo", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useQ","Use Q automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useW","Use W automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useE","Use E automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useR","Use R automaticly", SCRIPT_PARAM_ONOFF, false)
							Menu.ComboMenu:addParam("useRkill","- only when enemy is killable", SCRIPT_PARAM_ONOFF, false)
							Menu.ComboMenu:addParam("useRpos","- to get best position for Q return", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useRleft","- use rest of jumps at end", SCRIPT_PARAM_ONOFF, true)
							Menu.ComboMenu:addParam("useDFG","Use Deathfire Grasp in combo", SCRIPT_PARAM_ONOFF, true)
							Menu:addSubMenu("Harras settings", "Harras")
							Menu.Harras:addParam("Key", "Harras hotkey", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("A"))
							Menu.Harras:addParam("PriorizeE","Use E to get bonus DMG", SCRIPT_PARAM_ONOFF, true)
							Menu.Harras:addParam("useQ","Use Q automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu.Harras:addParam("useW","Use W automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu.Harras:addParam("useE","Use E automaticly", SCRIPT_PARAM_ONOFF, true)
							Menu:addSubMenu("Drawer settings", "DrawHelperMenu")
							Menu.DrawHelperMenu:addParam("drawQrange","Draw Q range", SCRIPT_PARAM_ONOFF, false)
							Menu.DrawHelperMenu:addParam("drawWrange","Draw W range", SCRIPT_PARAM_ONOFF, false)
							Menu.DrawHelperMenu:addParam("drawErange","Draw E range", SCRIPT_PARAM_ONOFF, true)
							Menu.DrawHelperMenu:addParam("drawRrange","Draw R range", SCRIPT_PARAM_ONOFF, false)
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
		if AhriOrb ~= nil and not AhriOrb.valid then AhriOrb = nil end
		CanQ = (myHero:CanUseSpell(_Q) == READY)
		CanW = (myHero:CanUseSpell(_W) == READY)
		CanE = (myHero:CanUseSpell(_E) == READY)
		CanR = (myHero:CanUseSpell(_R) == READY)
		CanIgnite = (iSlot ~= nil and myHero:CanUseSpell(iSlot) == READY)
		CanDFG = GetInventoryItemIsCastable(3128)
		if ts.target ~= nil then
			local Qdmg = 0
			local Wdmg = 0
			local Edmg = 0
			local Rdmg = 0
			local DFGdmg = 0
			local IGNdmg = 0
			if CanQ then
				local x = (15+myHero:GetSpellData(_Q).level*25)+(myHero.ap*0.325)
				local x1 = myHero:CalcMagicDamage(ts.target, x)
				Qdmg = x + x1
			end
			if CanW then Wdmg = getDmg("W", ts.target, myHero)*1.6 end
			if CanE then Edmg = getDmg("E", ts.target, myHero)*1 end
			if CanR then Rdmg = getDmg("R", ts.target, myHero)*GetUltStacks() end
			if CanDFG then DFGdmg = getDmg("DFG", ts.target, myHero) end
			if CanIgnite then IGNdmg = getDmg("IGNITE", ts.target, myHero) end
			if CanDFG then
				CurrentDPS = (Qdmg+Wdmg+Edmg+Rdmg+DFGdmg+IGNdmg)*1.2
			else
				CurrentDPS = Qdmg+Wdmg+Edmg+Rdmg+DFGdmg+IGNdmg
			end
		end
		if Menu.ComboMenu.ComboKey then
			if ts.target ~= nil then
				if GetDistance(ts.target) < 900 then
					if Menu.ComboMenu.useAA then OrbwalkTarget(ts.target) end
					if Menu.ComboMenu.smartQWER then
						local TotalDMG = 0
						local DFGdmg = 0
						local Qdmg = 0
						local Wdmg = 0
						local Edmg = 0
						local Rdmg = 0
						if CanDFG then DFGdmg = getDmg("DFG", ts.target, myHero) end
						if CanQ then Qdmg = getDmg("Q", ts.target, myHero) end
						if CanW then Wdmg = getDmg("W", ts.target, myHero)*2 end
						if CanE then Edmg = getDmg("E", ts.target, myHero) end
						if CanR then Rdmg = getDmg("R", ts.target, myHero)*GetUltStacks() end
						if CanDFG then
							TotalDMG = (Qdmg+Wdmg+Edmg+Rdmg+DFGdmg)*1.2
						else
							TotalDMG = Qdmg+Wdmg+Edmg+Rdmg
						end
						local tt = ts.target
						if tt.health < Wdmg and CanW and GetDistance(tt) < 800 then
							CastW(tt)
						elseif tt.health < Qdmg and CanQ and GetDistance(tt) < 850 then
							CastQ(tt)
						elseif tt.health < Qdmg+Wdmg and CanQ and CanW and GetDistance(tt) < 800 then
							CastQ(tt)
							CastW(tt)
						elseif tt.health < Qdmg+Wdmg+Edmg and CanQ and CanW and CanE and GetDistance(tt) < 800 then
							local ECollide = tECollision:GetMinionCollision(myHero, ts.target)
							if CanQ and not CanE or CanQ and ECollide then CastQ(ts.target) end
							if CanW and not CanE or CanW and ECollide then CastW(ts.target) end
							if CanE then CastE(ts.target) end
						elseif tt.health < Qdmg+Wdmg+Edmg+Rdmg and CanQ and CanW and CanE and CanR and CanDFG then
							ECollide = tECollision:GetMinionCollision(myHero, ts.target)
							if CanQ and not CanE or CanQ and ECollide then CastQ(ts.target) end
							if CanW and not CanE or CanW and ECollide then CastW(ts.target) end
							if CanE then CastE(ts.target) end
							local RandomVector = Point(ts.target.x+math.random(-50, 50), ts.target.z+math.random(-50, 50))
							local CastTo = Point(ts.target.x, ts.target.z)-(RandomVector-Point(ts.target.x, ts.target.z)):normalized()*250
							if Menu.ComboMenu.useR then CastSpell(_R, CastTo.x, CastTo.y) end
						else
							local RandomVector = Point(ts.target.x+math.random(-50, 50), ts.target.z+math.random(-50, 50))
							local CastTo = Point(ts.target.x, ts.target.z)-(RandomVector-Point(ts.target.x, ts.target.z)):normalized()*250
							if Menu.ComboMenu.useR and CurrentDPS > tt.health then CastSpell(_R, CastTo.x, CastTo.y) end
							if CanIgnite and CurrentDPS > tt.health then CastSpell(iSlot, tt) end
							if CanDFG then CastItem(3128, tt) end
							local ECollide = tECollision:GetMinionCollision(myHero, ts.target)
							if CanQ and not CanE or CanQ and ECollide then CastQ(ts.target) end
							if CanW and not CanE or CanW and ECollide then CastW(ts.target) end
							if CanE then CastE(ts.target) end
						end	
					else
						if CanDFG and Menu.ComboMenu.useDFG then CastItem(3128, ts.target) end
						if CanE then CastE(ts.target) end
						if CanQ then CastQ(ts.target) end
						if CanW then CastW(ts.target) end
						if Menu.ComboMenu.useRkill and getDmg("R", ts.target, myHero)*GetUltStacks() then
							if CanR and Menu.ComboMenu.useR then
								local RandomVector = Point(ts.target.x+math.random(-50, 50), ts.target.z+math.random(-50, 50))
								local CastTo = Point(ts.target.x, ts.target.z)-(RandomVector-Point(ts.target.x, ts.target.z)):normalized()*250
								CastSpell(_R, CastTo.x, CastTo.y)
							end
						else
							if CanR and Menu.ComboMenu.useR then
								local RandomVector = Point(ts.target.x+math.random(-50, 50), ts.target.z+math.random(-50, 50))
								local CastTo = Point(ts.target.x, ts.target.z)-(RandomVector-Point(ts.target.x, ts.target.z)):normalized()*250
								CastSpell(_R, CastTo.x, CastTo.y)
							end
						end
					end
					if Menu.ComboMenu.useR and GetUltStacks() > 0 then
						if Menu.ComboMenu.useRpos then
							if not CanQ and AhriOrb ~= nil and AhriOrb.valid and not ts.target.dead and GetDistance(AhriOrb) > GetDistance(ts.target) then
								local Position1 = Point(ts.target.x, ts.target.z)-(Point(AhriOrb.x, AhriOrb.z)-Point(ts.target.x, ts.target.z)):normalized()*250
								if Position1 ~= nil and GetDistance(Position1) < 500 then
									if GetUltStacks() > 0 then
										CastSpell(_R, Position1.x, Position1.y)
									end
								end
							end
						end
						if Menu.ComboMenu.useRks then
							if ts.target.health > getDmg("R", ts.target, myHero)*GetUltStacks() then
								local RandomVector = Point(ts.target.x+math.random(-50, 50), ts.target.z+math.random(-50, 50))
								local CastTo = Point(ts.target.x, ts.target.z)-(RandomVector-Point(ts.target.x, ts.target.z)):normalized()*250
								CastSpell(_R, CastTo.x, CastTo.y)
							end
						end
					end
				elseif GetDistance(ts.target) > 900 and GetDistance(ts.target) < 1250 then
					if Menu.ComboMenu.useRkill and CurrentDPS > ts.target.health then
						if CanR and Menu.ComboMenu.useR then CastSpell(_R, ts.target.x, ts.target.z) end
					elseif not Menu.ComboMenu.useRkill then
						if CanR and Menu.ComboMenu.useR then CastSpell(_R, ts.target.x, ts.target.z) end
					end
					if Menu.ComboMenu.useAA then OrbwalkTarget() end
				else
					PrintChat("Error #13 - Report it to Anonymous, write about situation!")
				end
			else
				if Menu.ComboMenu.useAA then OrbwalkTarget() end
			end
		end
		if Menu.Harras.Key then
			if ts.target ~= nil then
				if Menu.Harras.PriorizeE then
					local ECollide = tECollision:GetMinionCollision(myHero, ts.target)
					if CanQ and not CanE or CanQ and ECollide then CastQ(ts.target) end
					if CanW and not CanE or CanW and ECollide then CastW(ts.target) end
					if CanE then CastE(ts.target) end
				else
					if CanQ then CastQ(ts.target) end
					if CanW then CastW(ts.target) end
					if CanE then CastE(ts.target) end
				end
			end
		end
		if ts.target ~= nil then
			if Menu.ComboMenu.useR and Menu.ComboMenu.useRleft and GetUltStacks() > 0 and GetUltStacks() < 3 then
				local FiringTime = GetUltStacks()*1000+100
				local TimeToEnd = EndOfR-GetTickCount()
				if TimeToEnd < FiringTime then
					local Position1 = Point(ts.target.x, ts.target.z)-(Point(ts.target.x, ts.target.z)-Point(myHero.x, myHero.z)):normalized()*450
					if Position1 ~= nil then
						CastSpell(_R, Position1.x ,Position1.y)
					end
				end
			end
		end
	end
end
function OrbwalkTarget(Target)
	if Target == nil then 
		MoveTo(mousePos.x, mousePos.z)
	else
		if CanShoot() and GetDistance(Target) < 550 then
			Attack(Target)
		else
			if CanMove() then MoveTo(mousePos.x, mousePos.z) end
		end
	end
end

function CanShoot()
	if GetTickCount() >= NextAATick then return true else return false end
end
function CanMove()
	if GetTickCount() >= EndOfWindupTick then return true else return false end
end
function MoveTo(toX, toZ)
	local FM = Point(myHero.x, myHero.z)-(Point(myHero.x, myHero.z)-Point(toX, toZ)):normalized()*250
	Packet('S_MOVE', {type = 2, x=FM.x, y=FM.y}):send()
end
function Attack(target)
	if target ~= nil and target.valid and not target.dead and GetDistance(target) <= 550 then
		myHero:Attack(target)
	end
end
function CastQ(Target)
	if Menu.ComboMenu.useQ and Target ~= nil and not Target.dead then
		local pos = QProdict:GetPrediction(Target)
		if pos ~= nil and GetDistance(pos) < 850 then
			CastSpell(_Q, pos.x, pos.z)
		end
	end
end
function CastW(Target)
	if Menu.ComboMenu.useW and Target ~= nil and Target.valid and not Target.dead then
		if GetDistance(Target) < 700 then
			CastSpell(_W)
		end
	end
end
function CastE(Target)
	if Menu.ComboMenu.useE and Target ~= nil and Target.valid and not Target.dead then
		local pos = EProdict:GetPrediction(Target, myHero)
		if not tECollision:GetMinionCollision(myHero, pos) and pos ~= nil and GetDistance(pos) <= 900 then
			CastSpell(_E, pos.x, pos.z)
		end
	end
end
function OnProcessSpell(unit, spell)
	if scriptReady then
		if unit.isMe and (spell.name:lower():find("attack")) then
			NextAATick = (spell.animationTime*1000)+GetTickCount() - GetLatency()/2
			EndOfWindupTick = (spell.windUpTime*1000)+GetTickCount() - GetLatency()/2
		end
	end
end
function OnDraw()
	if scriptReady then
		if Menu.DrawHelperMenu.drawQrange then DrawCircle(myHero.x, myHero.y, myHero.z, 880, RGB(0,191,255)) end
		if Menu.DrawHelperMenu.drawWrange then DrawCircle(myHero.x, myHero.y, myHero.z, 800, RGB(0,191,255)) end
		if Menu.DrawHelperMenu.drawErange then DrawCircle(myHero.x, myHero.y, myHero.z, 975, RGB(0,191,255)) end
		if Menu.DrawHelperMenu.drawRrange then DrawCircle(myHero.x, myHero.y, myHero.z, 450*GetUltStacks(), RGB(255,0,255)) end
		if Menu.DrawHelperMenu.drawHPtext then
			for n, DrawTarget in pairs(Enemies) do
				if DrawTarget ~= nil and DrawTarget.valid and not DrawTarget.dead and DrawTarget.team == TEAM_ENEMY then
					if DrawTarget ~= nil then
						local Qdmg = 0
						local Wdmg = 0
						local Edmg = 0
						local Rdmg = 0
						local DFGdmg = 0
						local IGNdmg = 0
						if CanQ then
							local x = (15+myHero:GetSpellData(_Q).level*25)+(myHero.ap*0.325)
							local x1 = myHero:CalcMagicDamage(DrawTarget, x)
							Qdmg = x + x1
						end
						if CanW then Wdmg = getDmg("W", DrawTarget, myHero)*1.6 end
						if CanE then Edmg = getDmg("E", DrawTarget, myHero)*1 end
						if CanR then Rdmg = getDmg("R", DrawTarget, myHero)*GetUltStacks() end
						if CanDFG then DFGdmg = getDmg("DFG", DrawTarget, myHero) end
						if CanIgnite then IGNdmg = getDmg("IGNITE", DrawTarget, myHero) end
						if CanDFG then
							CurrentDPS = (Qdmg+Wdmg+Edmg+Rdmg+DFGdmg+IGNdmg)*1.2
						else
							CurrentDPS = Qdmg+Wdmg+Edmg+Rdmg+DFGdmg+IGNdmg
						end
					end
					local Qdmg = 0
					local Wdmg = 0
					local Edmg = 0
					if CanQ then
						local x = (15+myHero:GetSpellData(_Q).level*25)+(myHero.ap*0.325)
						local x1 = myHero:CalcMagicDamage(DrawTarget, x)
						Qdmg = x + x1
					end
					if CanW then Wdmg = getDmg("W", DrawTarget, myHero)*1.60 end
					if CanE then Edmg = getDmg("E", DrawTarget, myHero) end
					if Qdmg+Wdmg+Edmg > DrawTarget.health then
						if waittxt[n] == 1 then 
							PrintFloatText(DrawTarget, 0, "MURDER HIM")
							waittxt[n] = 10
						else
							waittxt[n] = waittxt[n]-1
						end
					elseif CurrentDPS > DrawTarget.health then
						if waittxt[n] == 1 then 
							PrintFloatText(DrawTarget, 0, "Killable")
							waittxt[n] = 10
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