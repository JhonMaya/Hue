<?php exit() ?>--by x7x 89.70.161.4


if myHero.charName ~= "Nunu" and myHero.charName ~= "Pantheon" and myHero.charName ~= "MissFortune" and myHero.charName ~= "MasterYi" and myHero.charName ~= "Malzahar" and myHero.charName ~= "Katarina" and myHero.charName ~= "Janna" and myHero.charName ~= "Fiddlesticks" then return end

local lastAnimation = "Run"
local isSAC = false
local isSACr = false
local isMMA = false
local isEVD = false
local useSAC = _G.ABUuseSACRevamped
local useSACr = _G.ABUuseSACReborn
local useMMA = _G.ABUuseMMA
local useEVD = _G.ABUuseEvadeee
local usePKT = _G.ABUusePackets

function OnLoad()
	PrintChat("<font color='#00BFFF'>ABU v1.0 >> Loaded!</font>")
end

function OnAnimation(unit, animationName)
	if unit.isMe and lastAnimation ~= animationName then lastAnimation = animationName end
end

function isChanneling()
	if myHero.charName == "Nunu" or myHero.charName == "MissFortune" or myHero.charName == "Malzahar" or myHero.charName == "Katarina" or myHero.charName == "Janna" then
		if lastAnimation == "Spell4" or lastAnimation == "spell4" or lastAnimation == "Spell4_Windup" or lastAnimation == "Spell4_Winddown" or lastAnimation == "Spell4_Loop" then
			return true
		else
			return false
		end
	elseif myHero.charName == "Pantheon" then
		if lastAnimation == "Spell3" or lastAnimation == "spell3" then
				return true
			else
				return false
			end
	elseif myHero.charName == "MasterYi" or myHero.charName == "Fiddlesticks" then
		if lastAnimation == "Spell2" or lastAnimation == "spell2" then
			return true
		else
			return false
		end
	end	
end

function OnSendPacket(p)
	local Packet = Packet(p)
	if p ~= nil and usePKT then
		if p.header == Packet.headers.S_MOVE or p.header == Packet.headers.S_CAST then
			if isChanneling() then
				p:Block()
			end
		end
	end
end

function DetectScripts()
	if not isSAC and useSAC then
		if _G.AutoCarry.CanMove ~= nil and _G.AutoCarry.CanAttack ~= nil and _G.AutoCarry.MyHero == nil then
			isSAC = true
			PrintChat("<font color='#00BFFF'>ABU v1.0 >> SAC: Revamped detected!</font>")
		end
	end
	if not isMMA and useMMA then
		if _G.MMA_Loaded then
			isMMA = true
			PrintChat("<font color='#00BFFF'>ABU v1.0 >> Marksman Mighty Assistant detected!</font>")
		end
	end
	if not isSACr and useSACr then
		if _G.AutoCarry.MyHero ~= nil then
			isSACr = true
			PrintChat("<font color='#00BFFF'>ABU v1.0 >> SAC: Reborn detected!</font>")
		end
	end
	if not isEVD and useEVD then
		if _G.evading ~= nil then
			isEVD = true
			PrintChat("<font color='#00BFFF'>ABU v1.0 >> Evadeee detected!</font>")
		end
	end
end

function OnTick()
	DetectScripts()
	if isChanneling() then
		if isSAC and useSAC then
			_G.AutoCarry.CanMove = false
			_G.AutoCarry.CanAttack = false
		end
		if isSACr and useSACr then
			_G.AutoCarry.MyHero:MovementEnabled(false)
			_G.AutoCarry.MyHero:AttacksEnabled(false)
		end
		if isMMA and useMMA then
			_G.MMA_AbleToMove = false
		end
		if isEVD and useEVD then
			_G.evading = false
		end
	else
		if isSAC and useSAC then
			_G.AutoCarry.CanMove = true
			_G.AutoCarry.CanAttack = true
		end
		if isSACr and useSACr then
			_G.AutoCarry.MyHero:MovementEnabled(true)
			_G.AutoCarry.MyHero:AttacksEnabled(true)
		end
		if isMMA and useMMA then
			_G.MMA_AbleToMove = true
		end
		if isEVD and useEVD then
			_G.evading = true
		end
	end
end